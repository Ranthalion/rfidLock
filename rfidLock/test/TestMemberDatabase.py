#!/usr/bin/python3

import unittest
import sqlite3
from context import rfidLock
from rfidLock import MemberDatabase
from os import remove
import random, string

class TestMemberDatabaseHash(unittest.TestCase):
  def test_hash_mutates(self):
    # Check that the hash isn't a nop 
    self.assertNotEqual(MemberDatabase.hash(None, "hello".encode()), "hello".encode())
  def test_hash_does_not_collide(self):
    # Check that hash doesn't collide with 2000 unique random strings
    self.assertEqual(len(frozenset([MemberDatabase.hash(None, ''.join(random.sample(string.ascii_lowercase + string.ascii_uppercase + string.digits, 10)).encode()) for i in range(0, 2000)])), 2000)

class TestMemberDatabaseCreate(unittest.TestCase):
  db_path = "/tmp/test_member_database_create.db"
  def setUp(self):
    self.db = sqlite3.connect(self.db_path)
    self.member_db = MemberDatabase(self.db, "?")
  def tearDown(self):
    # close the connection and delete the object
    self.db.close()
    remove(self.db_path)
  def test_can_create(self):
    # Check that database creation doesn't cause an error
    self.member_db.create()
  def test_double_creation_fails(self):
    # Check that trying to create an existing database throws an error
    self.member_db.create()
    with self.assertRaises(self.db.OperationalError):
      self.member_db.create()

class TestMemberDatabaseDestroy(unittest.TestCase):
  db_path = "/tmp/test_member_database_destroy.db"
  def setUp(self):
    self.db = sqlite3.connect(self.db_path)
    self.member_db = MemberDatabase(self.db, "?")
  def tearDown(self):
    # close the connection and delete the object
    self.db.close()
    remove(self.db_path)
  def test_creation_destruction_cycles_work(self):
    # Check that database creation doesn't cause an error
    self.member_db.create()
    self.member_db.destroy()
    self.member_db.create()
    self.member_db.destroy()

class TestMemberDatabaseClear(unittest.TestCase):
  pass

class TestMemberDatabaseMimic(unittest.TestCase):
  pass

class TestMemberDatabaseAdd(unittest.TestCase):
  db_path = "/tmp/test_member_database_add.db"
  def setUp(self):
    self.db = sqlite3.connect(self.db_path)
    self.member_db = MemberDatabase(self.db, "?")
    self.member_db.create()
  def tearDown(self):
    # close the connection and delete the object
    self.db.close()
    remove(self.db_path)
  def test_add_member_does_not_fail(self):
    self.member_db.add(b'test_data', "John Smith", "js@hackrva.org")

class TestMemberDatabaseHave(unittest.TestCase):
  db_path = "/tmp/test_member_database_add.db"
  def setUp(self):
    self.db = sqlite3.connect(self.db_path)
    self.member_db = MemberDatabase(self.db, "?")
    self.member_db.create()
    self.member_db.add(b'test_data', "John Smith", "js@hackrva.org")
  def tearDown(self):
    # close the connection and delete the object
    self.db.close()
    remove(self.db_path)
  def test_checks_member_existence(self):
    self.assertTrue(self.member_db.have(b'test_data'))
  def test_checks_member_non_existence(self):
    self.assertFalse(self.member_db.have(b'bad_test_data'))

class TestMemberDatabaseRevoke(unittest.TestCase):
  pass

class TestMemberDatabaseRevoked(unittest.TestCase):
  pass

class TestMemberDatabaseReinstate(unittest.TestCase):
  pass

class TestMemberDatabaseHaveCurrent(unittest.TestCase):
  pass

class TestMemberDatabaseList(unittest.TestCase):
  pass

if __name__ == '__main__':
  unittest.main()

