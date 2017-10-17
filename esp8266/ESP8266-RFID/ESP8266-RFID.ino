#include <FS.h>

#include <stdlib.h>
#include <Ticker.h>
#include <SoftwareSerial.h>
#include <ESP8266WiFi.h>
#include <DNSServer.h>
#include <ESP8266WebServer.h>
#include <WiFiManager.h>
#include <ArduinoJson.h>
#include <ESP8266HTTPClient.h>

int LED_WIFI = 0;
int LED_REJECT = 16;
int LED_CONFIRM = 5;
int RESET_BUTTON = 14;
//TODO: Add Lock button
//TODO: Add Unlock pin

char verify_url[80];
char resource_name[80];

Ticker ticker;
int count = 0;
int confirmCount = 0;
int rejectCount = 0;

long rfid;
String inputString = "";         // a String to hold incoming data
boolean stringComplete = false;  // whether the string is complete

boolean connectedToWifi = false;

SoftwareSerial swSerial(13, 15);

bool shouldSaveConfig = false;

void setup() {

  // Set up GPIO
  digitalWrite(LED_WIFI, HIGH);
  digitalWrite(LED_CONFIRM, HIGH);  
  digitalWrite(LED_REJECT, HIGH);
  pinMode(LED_WIFI, OUTPUT);
  pinMode(LED_CONFIRM, OUTPUT);
  pinMode(LED_REJECT, OUTPUT);
  pinMode(RESET_BUTTON, INPUT_PULLUP);
  
  // Set up timer
  ticker.attach(0.25, timer_tick);  
  
  // Set up serial
  inputString.reserve(200);
  Serial.begin(9600);
  swSerial.begin(9600);

  //Read configuration data
  if (SPIFFS.begin()) {
    if (SPIFFS.exists("/config.json")) {
      //file exists, reading and loading
      File configFile = SPIFFS.open("/config.json", "r");
      if (configFile) {
  
        size_t size = configFile.size();
        // Allocate a buffer to store contents of the file.
        std::unique_ptr<char[]> buf(new char[size]);

        configFile.readBytes(buf.get(), size);
        
        DynamicJsonBuffer jsonBuffer;
        JsonObject& json = jsonBuffer.parseObject(buf.get());
  
        if (json.success()) {
          strcpy(verify_url, json["verify_url"]);
          strcpy(resource_name, json["resource_name"]);
        } else {
          //TODO: Locally keep track of the error for whatever reason
          Serial.println("failed to load json config");
        }
      }
    }
  } else {
    //TODO: Locally keep track of the error for whatever reason
    Serial.println("failed to mount FS");
  }
  
  // Set up wifi 
  connectedToWifi = false;
  WiFiManager wifiManager;
  wifiManager.setSaveConfigCallback(saveConfigCallback);
  WiFiManagerParameter verify_url_param("url", "verify url", verify_url, 80);
  wifiManager.addParameter(&verify_url_param);
  WiFiManagerParameter resource_name_param("name", "resource name", resource_name, 80);
  wifiManager.addParameter(&resource_name_param);
  
  //reset wifi settings if button is pressed
  if (digitalRead(RESET_BUTTON) == LOW)
  {
    wifiManager.resetSettings();
    SPIFFS.format();
  }
  
  wifiManager.autoConnect("AutoConnectAP");
  connectedToWifi = true;
  
  strcpy(verify_url, verify_url_param.getValue());
  strcpy(resource_name, resource_name_param.getValue());

  //save the custom parameters to FS
  if (shouldSaveConfig) {
    DynamicJsonBuffer jsonBuffer;
    JsonObject& json = jsonBuffer.createObject();
    json["verify_url"] = verify_url;
    json["resource_name"] = resource_name;

    File configFile = SPIFFS.open("/config.json", "w");
    if (!configFile) {
      //TODO: Locally keep track of the error for whatever reason
      Serial.println("failed to open config file for writing");
    }

    json.printTo(configFile);
    configFile.close();
    //end save
  }
}

void loop() {

  char x[15];

   while (swSerial.available()) {
    char inChar = (char)swSerial.read();
    inputString += inChar;

    //Hacky way to read all the bytes from a single fob
    if (inputString.length() >= 14){
      //Hack to ignore start byte, version/customer Id, checksum, and stop byte
      inputString = inputString.substring(5, 11);

      //Convert the ascii hex value into a decimal number
      inputString.toCharArray(x, 7);
      rfid = strtol(x, NULL, 16);
      
      stringComplete = true;
    }
  }
  
  if (stringComplete) {
    //Call web api to verify rfid
    
    //TODO: Do something with an indicator LED to show pending
    HTTPClient http;

    //Convert the decimal to string
    ltoa(rfid, x, 10);
    
    String payload = "?rfid=" + urlencode(x) + "&resource=" + urlencode(resource_name);
    String url = verify_url;
    http.begin(url + payload);
    int httpCode = http.GET();

    // httpCode will be negative on error
    if(httpCode == HTTP_CODE_OK) {
      String payload = http.getString();
      
      DynamicJsonBuffer jsonBuffer;
      JsonObject& json = jsonBuffer.parseObject(payload.c_str());
      
      if (json.success()) {
        bool verified = json["verified"];
        if (verified == true)
        {
          confirmCount = 4;
          //Unlock
        }
        else
        {
          rejectCount = 4;
        }
        
      } else {
        //TODO: Locally keep track of the error for whatever reason
        Serial.println("failed to parse response");
      }
      //TODO: Parse as JSON and see if verified is true
      
    } else {
      //TODO: Locally keep track of the error for whatever reason
      Serial.printf("[HTTP] GET... failed, error: %s\n", http.errorToString(httpCode).c_str());
    }

    http.end();

    inputString = "";
    stringComplete = false;
  }
}

void timer_tick()
{
  ++count;
  
  if (connectedToWifi == true)
  {
    //connected to wifi
    digitalWrite(LED_WIFI, LOW);
  }
  else
  {
    //searching for wifi
    digitalWrite(LED_WIFI, ((count & 0x01) == 0));
  }

  if (confirmCount > 0)
  {
    digitalWrite(LED_CONFIRM, LOW);
    confirmCount--;
  }
  else
  {
    digitalWrite(LED_CONFIRM, HIGH);
  }

  if (rejectCount > 0)
  {
    digitalWrite(LED_REJECT, LOW);
    rejectCount--;
  }
  else if(rejectCount == 0)
  {
    digitalWrite(LED_REJECT, HIGH);
  }
}

void saveConfigCallback () {
  shouldSaveConfig = true;
}

String urlencode(String str)
{
    String encodedString="";
    char c;
    char code0;
    char code1;

    for (int i =0; i < str.length(); i++){
      c=str.charAt(i);
      if (c == ' '){
        encodedString+= '+';
      } else if (isalnum(c)){
        encodedString+=c;
      } else{
        code1=(c & 0xf)+'0';
        if ((c & 0xf) >9){
            code1=(c & 0xf) - 10 + 'A';
        }
        c=(c>>4)&0xf;
        code0=c+'0';
        if (c > 9){
            code0=c - 10 + 'A';
        }
        encodedString+='%';
        encodedString+=code0;
        encodedString+=code1;
      }
      yield();
    }
    return encodedString;    
}

