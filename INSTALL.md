# Database Installation Instructions

These instructions are for purposes of getting the Python package installed
and configured on the Raspberry Pi that controls the door. There is also
a Python based web interface

## Install Using pip

The Python package needs to be installed on both the Raspberry Pi and the
server.

```bash
git clone https://github.com/ranthalion/rfidLock.git
sudo pip install rfidLock/
```

## Configuring the RFID System

During installation a JSON file was created at `/etc/rfidlock/config.json`

This file needs to be updated differently on the Raspberry Pi and the server.

On the Raspberry Pi the "database" entry needs to be updated with 
information to connect to the remote database while the `local_database`
entry needs to be set to the path for the local sqlite database.

On the server, the "database" entry needs to be updated with information
to connect to the authoratative database which is most likely local to
that server.

In both cases, the configuration entries for the "database" entry correspond
to the keyword arguments for the [`mysql.connector.connect` function call](https://dev.mysql.com/doc/connector-python/en/connector-python-connectargs.html).
A user

## Create Database

Before installing the databases, users need to be created in MySQL for access
to the database and the database itself must be created. In the following 
commands the *HOSTNAME* for the *RFIDLOCK_DOOR* user needs to be the hostname 
or IP address of the Raspberry Pi. In the following commands the *HOSTNAME* for
the *RFIDLOCK_MANAGER* user needs to be the hostname or IP address of the 
server, which will be localhost if the database is hosted on the same computer
as the web gui. Naturally, in both cases the passwords need to be set with
appropriately hard to brute force or guess passwords.

The user names and passwords generated in this step need to be set in their
appropriate `config.json` files on the appropriate installs of the rfidlock
package.

```MySQL
CREATE DATABASE rfidlock;
CREATE USER *RFIDLOCK_MANAGER*@*HOSTNAME* IDENTIFIED BY "*RFIDLOCK_MANAGER_PASSWORD*";
CREATE USER *RFIDLOCK_DOOR*@*HOSTNAME* IDENTIFIED BY "*RASPBERRY_PI_PASSWORD*";
GRANT CREATE, SELECT, UPDATE ON rfidlock.* TO *RFIDLOCK_MANAGER*@*HOSTNAME*; 
GRANT SELECT ON rfidlock.* TO rfidlock_door@*HOSTNAME*;
```

## Create Tables

After the `config.json` files are properly configured, the database system then
needs to be initialized. The `rfid_db_install` script will create the necessary
tables for the RFID database. The databases should now be ready and configured
for use with the RFID Lock software.

## Start RFID Lock Software on Raspberry Pi

Provided that the databases are correctly installed and the Raspberry Pi has
been correctly connected to the door and RFID hardware, the Raspberry Pi can
run the `rfid_door` command to start .

By default, the Raspberry Pi is connected to inputs with a locking button on 
pin 23 and an RFID read trigger on pin 17. The outputs are connected with the
door locking action triggered on pin 7 and the door unlocking action triggered
on pin 8. The RFID serial reader is connected on the `/dev/AMA0` interface. All
of these pin and interface settings may be changed in the Raspberry Pi's 
`config.json` file.

# Python Web Interface Installation

This is not to install the Laravel interface. This provides a Jinja template
based interface that is likely to be dropped in favor of the Laravel interface
in the future.

## Install and Configure WSGI

If the Python based web interface is desired to be used, the WSGI script that
provides this interface must be configured with Apache.

Use the package manager as appropriate to your computer to install the Apache
WSGI module. After that module completes installation enable the module using
a2enmod

```bash
a2enmod wsgi
```

Find the path to the RFID wsgi script using the which command.

```bash
which rfid_db.wsgi
```

Next, add a line in your Apache site configuration file to alias the WSGI
script and a line to enable that script.

```
WSGIScriptAlias /rfid_db.wsgi /path/to/rfid_db.wsgi
<Directory "/">
  Require all granted
</Directory>
```

Finally, restart Apache and the Python web interface should be available at
`localhost/rfid_db.wsgi`.

