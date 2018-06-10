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
#include <SPI.h>

const byte RESET_BUTTON = D1;
const byte LED_WIFI     = D3;
const byte DOOR_SENSOR  = D4;
const byte BUZZER       = D5;
const byte SW_RX        = D7;
const byte SW_TX        = D8;
const byte LED_REJECT   = D2;
const byte LED_CONFIRM  = D6;

//Range 100 to 5000 Hz
unsigned int GOOD_TONE = 1500;
unsigned int BAD_TONE = 100;
unsigned int DEFAULT_TONE = 1000;

unsigned long SHORT_DURATION = 200;
unsigned long MEDIUM_DURATION = 500;
unsigned long LONG_DURATION = 700;

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
bool shouldSaveConfig = false;

SoftwareSerial swSerial(SW_RX, SW_TX);

bool loadConfig() {

  File configFile = SPIFFS.open("/config.json", "r");
  if (!configFile) {
    Serial.println("Failed to open config file");
    return false;
  }

  size_t size = configFile.size();
  if (size > 1024) {
    Serial.println("Config file size is too large");
    return false;
  }

  std::unique_ptr<char[]> buf(new char[size]);
  configFile.readBytes(buf.get(), size);

  DynamicJsonDocument jsonBuffer;
  DeserializationError error = deserializeJson(jsonBuffer, buf.get());
  if (error) {
    Serial.println("Unable to deserialize");
    return false;
  }

  JsonObject& json = jsonBuffer.as<JsonObject>();
  if (json.success()) {
    strcpy(verify_url, json["verify_url"]);
    strcpy(resource_name, json["resource_name"]);
    return true;
  } else {
    Serial.println("failed to parse json config");
    return false;
  }
}

bool writeConfig(){
  DynamicJsonDocument jsonBuffer;
  JsonObject& json = jsonBuffer.to<JsonObject>();
  json["verify_url"] = verify_url;
  json["resource_name"] = resource_name;

  File configFile = SPIFFS.open("/config.json", "w");
  if (!configFile) {
    Serial.println("failed to open config file for writing");
    return false;
  }

  serializeJson(json, configFile);
  serializeJson(jsonBuffer, Serial);
  configFile.close();
  
  return true;
}

void setup() {

  // Set up GPIO
  digitalWrite(LED_WIFI, HIGH);
  digitalWrite(LED_CONFIRM, HIGH);  
  digitalWrite(LED_REJECT, HIGH);
  pinMode(LED_WIFI, OUTPUT);
  pinMode(LED_CONFIRM, OUTPUT);
  pinMode(LED_REJECT, OUTPUT);
  pinMode(RESET_BUTTON, INPUT_PULLUP);
  pinMode(DOOR_SENSOR, INPUT_PULLUP);
  pinMode(BUZZER, OUTPUT);

  start_up_tone();
  
  // Set up timer
  ticker.attach(0.25, timer_tick);  
  
  // Set up serial
  inputString.reserve(200);
  Serial.begin(9600);
  swSerial.begin(9600);

  //Read configuration data
  if (!SPIFFS.begin()) {
    error_tone();
    Serial.println("Failed to mount file system");
    return;
  }
  
  if (!loadConfig()) {
    Serial.println("Failed to load config");
  } else {
    Serial.println("Config loaded");
  }
  
  
  // Set up wifi 
  connectedToWifi = false;
  WiFiManager wifiManager;
  wifiManager.setDebugOutput(false);
  
  wifiManager.setSaveConfigCallback(saveConfigCallback);
  WiFiManagerParameter verify_url_param("url", "verify url", verify_url, 80);
  wifiManager.addParameter(&verify_url_param);
  WiFiManagerParameter resource_name_param("name", "resource name", resource_name, 80);
  wifiManager.addParameter(&resource_name_param);
  
  //reset wifi settings if button is pressed
  if (digitalRead(RESET_BUTTON) == LOW)
  {
    tone(BUZZER, BAD_TONE, LONG_DURATION);
    wifiManager.resetSettings();
    SPIFFS.format();
  }

  wifiManager.autoConnect("RFID Setup");
  connectedToWifi = true;
  tone(BUZZER, GOOD_TONE, SHORT_DURATION);
  
  strcpy(verify_url, verify_url_param.getValue());
  strcpy(resource_name, resource_name_param.getValue());

  //save the custom parameters to FS
  if (shouldSaveConfig) {
    Serial.println("Saving Config");
    
    if (!writeConfig()) {
      Serial.println("Failed to load config");
    } else {
      Serial.println("Config saved");
    }
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
    tone(BUZZER, DEFAULT_TONE, 75);
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
      
      DynamicJsonDocument jsonBuffer;
      DeserializationError error = deserializeJson(jsonBuffer, payload.c_str());
      if (error) {
        //TODO: Handle error
        error_tone();
      }

      JsonObject& json = jsonBuffer.as<JsonObject>();
      
      if (json.success()) {
        bool verified = json["verified"];
        if (verified == true)
        {
          confirmCount = 16;
          tone(BUZZER, GOOD_TONE, MEDIUM_DURATION);
          //Unlock
        }
        else
        {
          rejectCount = 6;
          tone(BUZZER, BAD_TONE, LONG_DURATION);
        }
        
      } else {
        //TODO: Locally keep track of the error for whatever reason
        Serial.println("failed to parse response");
        error_tone();
      }
      //TODO: Parse as JSON and see if verified is true
      
    } else {
      Serial.printf("[HTTP] GET... failed, error: %s\n", http.errorToString(httpCode).c_str());
      //TODO: Locally keep track of the error for whatever reason
      error_tone();
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
    digitalWrite(LED_WIFI, HIGH);
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

  //TODO: Monitor the door sensor and report prolonged open states
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

void start_up_tone(){
  tone(BUZZER, BAD_TONE);
  delay(100);

  tone(BUZZER, DEFAULT_TONE);
  delay(100);
  
  tone(BUZZER, GOOD_TONE);
  delay(150);
  noTone(BUZZER);  
}

void error_tone()
{
  tone(BUZZER, BAD_TONE);
  delay(SHORT_DURATION);
  noTone(BUZZER);
  delay(SHORT_DURATION);
  tone(BUZZER, BAD_TONE, SHORT_DURATION);
}

