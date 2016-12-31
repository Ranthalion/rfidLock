# rfidLock
RFID Lock system

###Lamp Server
Primary Network IP: 10.200.200.11 up on eth0   
Secure Network IP: 172.12.123.20 up on eth1   
Software: Apache2, php7 w/all mods and oauth extension, mariaDB, phpMyAdmin   
Services: SSHD   

### Diagram
![Diagram](https://github.com/Ranthalion/rfidLock/blob/master/rfidLockDiagram.png "Diagram")

### Database Diagram
![Database Diagram](https://github.com/Ranthalion/rfidLock/blob/master/databaseDiagram.png "Database Diagram")

### Installation

#### Configuration File

A configuration file is necessary for managing the connection to the mysql 
database which is used as the authoratative data source. This file is also 
needed for other configuration details such as the location of templates.

The options that can be configured in this file's database parameter match with mysql.connector's 
[Python Connection Arguments](https://dev.mysql.com/doc/connector-python/en/connector-python-connectargs.html)

A simple example configuration file is shown here for reference.

```json
{
  "database": {
    "username": "rfid_user",
    "password": "db_passwd",
    "host": "rfid_host",
    "database": "rfid_db"
  },
  "templates": "/usr/share/rfidlock/templates"
}
```

#### Member Server

To install the member server and web interface:

```bash
git clone https://github.com/Ranthalion/rfidLock.git
sudo pip install rfidLock/
# Edit or create the config.json file described above
vim /etc/rfidlock/config.json
# Creates the necessary database tables
rfid_db_install

# For Arch, this should enable wsgi
echo "LoadModule wsgi_module modules/mod_wsgi.so" >> /etc/httpd/conf/httpd.conf
# For Debian/Ubuntu, this should enable wsgi
a2enmod wsgi

# Mount the WSGI script, again, this is for Arch, will be different on other distros
echo "WSGIScriptAlias /rfid_db.wsgi $(which rfid_db.wsgi)" >> /etc/httpd/conf/httpd.conf

# Install and enable mod_wsgi
# Modify Apache server configuration

# TODO run a script that installs an apache server site to a path
# Varies by distribution

# ? WSGIScriptAlias /wsgi /path/to/script/rfid_members.wsgi
# TODO configure apache server to run mod_wsgi
```

To remove the member server after installation:


#### Raspberry Pi
Secure Network IP: 172.12.123.98
```bash
git clone https://github.com/Ranthalion/rfidLock.git
sudo pip install rfidLock/
# TODO, establish a daemon that handles the RFID lock and enable it on start-up
```

