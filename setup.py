#!/usr/bin/python3

from distutils.core import setup

setup(
  name='rfidLock',
  version='0.1',
  packages = ['rfidLock'],
  scripts = [
    'bin/rfid_db_install',
    'bin/rfid_db_remove',
    'bin/member_db_wsgi'])

