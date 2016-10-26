#!/usr/bin/python

import unittest
import sqlite3
import json
import mysql.connector
from context import rfidLock
from rfidLock import MemberDatabase, DoorConnection
from os import remove

class TestDoorConnectionUpdate(unittest.TestCase):
  db_path_local = u'/tmp/test_door_connection_update_local.db'
  db_path_remote = u'/tmp/test_door_connection_update_remote.db'
  def setUp(self):
    self.db_local = sqlite3.connect(self.db_path_local)
    self.member_db_local = MemberDatabase(self.db_local, "?")
    self.member_db_local.create()
    self.db_remote = sqlite3.connect(self.db_path_remote)
    self.member_db_remote = MemberDatabase(self.db_remote, "?")
    self.member_db_remote.create()
    self.member_db_remote.add(b'test_data', u'John Smith', u'jsmith@hackrva.org')
    self.member_db_remote.add(b'dope_data', u'Crystal Meth', u'cmeth@hackrva.org')
    self.door_connection = DoorConnection(self.member_db_local, self.member_db_remote)
  def tearDown(self):
    self.db_local.close()
    self.db_remote.close()
    remove(self.db_path_local)
    remove(self.db_path_remote)
  def test_update_duplicates(self):
    self.door_connection.update()
    self.assertEqual(len(self.member_db_local.list()), 2)
    self.assertTrue(self.member_db_local.have(b'test_data'))
    self.assertTrue(self.member_db_local.have(b'dope_data'))

class TestDoorConnectionCheckRequest(unittest.TestCase):
  db_path_local = u'/tmp/test_door_connection_check_request_local.db'
  db_path_remote = u'/tmp/test_door_connection_check_request_remote.db'
  def setUp(self):
    self.db_local = sqlite3.connect(self.db_path_local)
    self.member_db_local = MemberDatabase(self.db_local, "?")
    self.member_db_local.create()
    self.db_remote = sqlite3.connect(self.db_path_remote)
    self.member_db_remote = MemberDatabase(self.db_remote, u'?')
    self.member_db_remote.create()
    self.member_db_remote.add(b'test_data', u'John Smith', u'jsmith@hackrva.org')
    self.member_db_remote.add(b'dope_data', u'Crystal Meth', u'cmeth@hackrva.org')
    self.door_connection = DoorConnection(self.member_db_local, self.member_db_remote)
  def tearDown(self):
    self.db_local.close()
    self.db_remote.close()
    remove(self.db_path_local)
    remove(self.db_path_remote)
  def test_remote_verifies(self):
    self.assertTrue(self.door_connection.check_request(b'test_data'))
    self.assertTrue(self.door_connection.check_request(b'dope_data'))
  def test_local_verifies_with_broken_remote(self):
    self.door_connection.update()
    self.db_remote.close()
    self.assertTrue(self.door_connection.check_request(b'test_data'))
    self.assertTrue(self.door_connection.check_request(b'dope_data'))
  def test_checking_syncs(self):
    self.assertTrue(self.door_connection.check_request(b'test_data'))
    self.assertTrue(self.door_connection.check_request(b'dope_data'))
    self.db_remote.close()
    self.assertTrue(self.door_connection.check_request(b'test_data'))
    self.assertTrue(self.door_connection.check_request(b'dope_data'))

# Need a mysql.connector connection to test this one
class TestDoorConnectionRecover(unittest.TestCase):
  db_path_local = u'/tmp/test_door_connection_check_recover_local.db'
  def setUp(self):
    config = {}
    with open(u'test_db.json') as config_file:
      config = json.load(config_file)
    self.db_local = sqlite3.connect(self.db_path_local)
    self.member_db_local = None
    self.has_mysql = True
    self.db_remote = None
    self.member_db_remote = None
    self.door_connection = None
    try:
      self.db_remote = mysql.connector.connect(**config)
    except:
      self.has_mysql = False
      print(u'MySQL DB Connection Failure')
    self.member_db_remote = MemberDatabase(self.db_remote, u'%s')
    if self.has_mysql:
      self.member_db_remote.create()
      self.member_db_remote.add(b'test_data', u'John Smith', u'jsmith@hackrva.org')
      self.member_db_remote.add(b'dope_data', u'Crystal Meth', u'cmeth@hackrva.org')
    self.member_db_local = MemberDatabase(self.db_local, u'?')
    self.member_db_local.create()
    self.door_connection = DoorConnection(self.member_db_local, self.member_db_remote)
  def tearDown(self):
    self.db_local.close()
    remove(self.db_path_local)
  def test_mysql_remote_verifies(self):
    if self.has_mysql:
      self.assertTrue(self.door_connection.check_request(b'test_data'))
      self.assertTrue(self.door_connection.check_request(b'dope_data'))
      self.member_db_remote.destroy()
      self.db_remote.close()
  def test_local_verifies_with_broken_mysql_remote(self):
    if self.has_mysql:
      self.door_connection.update()
      self.member_db_remote.destroy()
      self.db_remote.close()
      self.assertTrue(self.door_connection.check_request(b'test_data'))
      self.assertTrue(self.door_connection.check_request(b'dope_data'))
  def test_checking_syncs_mysql(self):
    if self.has_mysql:
      self.assertTrue(self.door_connection.check_request(b'test_data'))
      self.assertTrue(self.door_connection.check_request(b'dope_data'))
      self.member_db_remote.destroy()
      self.db_remote.close()
      self.assertTrue(self.door_connection.check_request(b'test_data'))
      self.assertTrue(self.door_connection.check_request(b'dope_data'))

if __name__ == '__main__':
  unittest.main()

