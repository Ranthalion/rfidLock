#!/usr/bin/python3

from distutils.core import setup

setup(
  name='rfidLock',
  version='0.1',
  packages = ['rfidLock'],
  scripts = [
    'bin/rfid_db_install',
    'bin/rfid_db_remove',
    'bin/rfid_db.wsgi'])

# TODO Create /etc/rfidlock/config.json

# TODO Install templates to /usr/share/rfidlock

