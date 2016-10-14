# rfidLock
RFID Lock system

###Lamp Server
Primary Network IP: 10.200.200.11 up on eth0   
Secure Network IP: 172.12.123.20 up on eth1   
Software: Apache2, php5 w/all mods, mariaDB, phpMyAdmin   
Services: SSHD   

### Diagram
![Diagram](https://github.com/Ranthalion/rfidLock/blob/master/rfidLockDiagram.png "Diagram")

### Installation

#### Database Configuration File

A database configuration file is necessary for managing the connection to the
mysql database which is used as the authoratative data source.

The options that can be configured in this file match with mysql.connector's 
[Python Connection Arguments](https://dev.mysql.com/doc/connector-python/en/connector-python-connectargs.html)

A simple example configuration file is shown here for reference.

```json
{
  "username": "rfid_user",
  "password": "db_passwd",
  "host": "rfid_host",
  "database": "rfid_db"
}
```

#### Member Server

To install the member server and web interface:

```bash
git clone https://github.com/Ranthalion/rfidLock.git
sudo pip install rfidLock/
rfid_db_install db_file.json

# TODO run a script that installs an apache server site to a path
# Varies by distribution

# ln -s /usr/local/scripts/.wsgi
# ? WSGIScriptAlias /wsgi /path/to/script/rfid_members.wsgi
# TODO configure apache server to run mod_wsgi
```

To remove the member server after installation:


#### Raspberry Pi

```bash
git clone https://github.com/Ranthalion/rfidLock.git
sudo pip install rfidLock/
# TODO, establish a daemon that handles the RFID lock and enable it on start-up
```

