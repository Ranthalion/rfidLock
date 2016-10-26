#!/usr/bin/python3

from setuptools import setup
from os import mkdir

setup(
  name='rfidLock',
  version='0.1',
  packages = ['rfidLock', 'rfidDoor'],
  scripts = [
    'bin/rfid_config',
    'bin/rfid_db_install',
    'bin/rfid_db_remove',
    'bin/rfid_db.wsgi',
    'bin/rfid_door'],
  install_requires = [
    # "Must not install sqlite3 from pypi"
    'mysql-connector',
    'RPIO',
    'jinja2'],
  package_data = {
    'rfidLock': ['templates/*.json', 'templates/*.html'],
  })

# TODO Create /etc/rfidlock/config.json


# TODO Install templates to /usr/share/rfidlock


