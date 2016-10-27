# Database Installation Instructions

These instructions are for purposes of getting the Python package installed
and configured on the Raspberry Pi that controls the door.

## Install Using pip

The Python package needs to be installed on both the Raspberry Pi and the
server.

```bash
git clone https://github.com/ranthalion/rfidLock.git
sudo pip install rfidLock/
```

## Configure Database

A user must be created for the RFID door system to access the MySQL using.

## Basic Configuration

A utility is provided to perform configuration. From the command line run the
`rfid_config` script.

A series of prompts will appear that must be filled out properly using the
values from the previous step. This supports most basic configurations, but 
more complex configurations are available through manipulation of 
`/etc/rfidlock/config.json` as seen in the *Advanced Database Configuration* 
section.

## Create Tables

A table or a table view need to be created on the MySQL server that the RFID
door will access.

After the `config.json` files are properly configured, the database system then
needs to be initialized. The `rfid_db_install` script will create the necessary
tables for the RFID database on the Raspberry Pi. The local database should now 
be ready and configured for use with the RFID Lock software.

## Start RFID Lock Software on Raspberry Pi

Provided that the databases are correctly installed and the Raspberry Pi has
been correctly connected to the door and RFID hardware, the Raspberry Pi can
run the `rfid_door` command to start reading RFID tags.

By default, the Raspberry Pi is connected to inputs with a locking button on 
pin 23 and an RFID read trigger on pin 17. The outputs are connected with the
door locking action triggered on pin 7 and the door unlocking action triggered
on pin 8. The RFID serial reader is connected on the `/dev/AMA0` interface. All
of these pin and interface settings may be changed in the Raspberry Pi's 
`config.json` file.

## Advanced Database Configuration

During configuration a JSON file was created at `/etc/rfidlock/config.json`

This file needs to be updated differently on the Raspberry Pi and the server.

On the Raspberry Pi the `database` entry needs to be updated with 
information to connect to the remote database while the `local_database`
entry needs to be set to the path for the local sqlite database.

On the server, the `database` entry needs to be updated with information
to connect to the authoratative database which is most likely local to
that server.

In both cases, the configuration entries for the `database` entry correspond
to the keyword arguments for the [`mysql.connector.connect` function call](https://dev.mysql.com/doc/connector-python/en/connector-python-connectargs.html).

