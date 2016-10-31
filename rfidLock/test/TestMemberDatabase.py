#!/usr/bin/python

import unittest
import sqlite3
from context import rfidLock
from rfidLock import MemberDatabase
from os import remove
import random, string
from datetime import datetime, timedelta

class TestMemberDatabaseHash(unittest.TestCase):
  def test_hash_mutates(self):
    # Check that the hash isn't a nop 
    self.assertNotEqual(MemberDatabase.hash("hello".encode()), "hello".encode())
  def test_hash_does_not_collide(self):
    # Check that hash doesn't collide with 2000 unique random strings
    self.assertEqual(len(frozenset([MemberDatabase.hash(''.join(random.sample(string.ascii_lowercase + string.ascii_uppercase + string.digits, 10)).encode()) for i in range(0, 2000)])), 2000)

class TestMemberDatabaseCreate(unittest.TestCase):
  db_path = "/tmp/test_member_database_create.db"
  def setUp(self):
    self.db = sqlite3.connect(self.db_path)
    self.member_db = MemberDatabase(self.db, "?", "member_table_v")
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
    self.member_db = MemberDatabase(self.db, "?", "member_table_v")
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

class TestMemberDatabaseAdd(unittest.TestCase):
  db_path = "/tmp/test_member_database_add.db"
  def setUp(self):
    self.db = sqlite3.connect(self.db_path)
    self.member_db = MemberDatabase(self.db, "?", "member_table_v")
    self.member_db.create()
  def tearDown(self):
    # close the connection and delete the object
    self.db.close()
    remove(self.db_path)
  def test_add_member_does_not_fail(self):
    self.member_db.add(b'test_data', "John Smith", "js@hackrva.org", datetime.now() + timedelta(days = 1))

class TestMemberDatabaseHave(unittest.TestCase):
  db_path = "/tmp/test_member_database_add.db"
  def setUp(self):
    self.db = sqlite3.connect(self.db_path)
    self.member_db = MemberDatabase(self.db, "?", "member_table_v")
    self.member_db.create()
    self.member_db.add(b'test_data', "John Smith", "js@hackrva.org", datetime.now() + timedelta(days = 1))
  def tearDown(self):
    # close the connection and delete the object
    self.db.close()
    remove(self.db_path)
  def test_checks_member_existence(self):
    self.assertTrue(self.member_db.have(b'test_data'))
  def test_checks_member_non_existence(self):
    self.assertFalse(self.member_db.have(b'bad_test_data'))

class TestMemberDatabaseHaveCurrent(unittest.TestCase):
  db_path = "/tmp/test_member_database_add.db"
  def setUp(self):
    self.db = sqlite3.connect(self.db_path)
    self.member_db = MemberDatabase(self.db, "?", "member_table_v")
    self.member_db.create()
    self.member_db.add(b'test_data', "John Smith", "js@hackrva.org", datetime.now() + timedelta(days = 1))
  def tearDown(self):
    # close the connection and delete the object
    self.db.close()
    remove(self.db_path)
  def test_checks_member_existence(self):
    self.assertTrue(self.member_db.have_current(b'test_data'))
  def test_checks_member_non_existence(self):
    self.assertFalse(self.member_db.have_current(b'bad_test_data'))

class TestMemberDatabaseList(unittest.TestCase):
  db_path = "/tmp/test_member_database_list.db"
  def setUp(self):
    self.db = sqlite3.connect(self.db_path)
    self.member_db = MemberDatabase(self.db, "?", "member_table_v")
    self.member_db.create()
    self.member_db.add(b'test_data', "John Smith", "jsmith@hackrva.org", datetime.now() + timedelta(days = 1))
    self.member_db.add(b'othe_data', "Crystal Meth", "cmeth@hackrva.org", datetime.now() + timedelta(days = 1))
  def tearDown(self):
    # close the connection and delete the object
    self.db.close()
    remove(self.db_path)
  def test_list_contains_users(self):
    member_list = self.member_db.list()
    self.assertEqual(len(member_list), 2)

class TestMemberDatabaseClear(unittest.TestCase):
  db_path = "/tmp/test_member_database_clear.db"
  def setUp(self):
    self.db = sqlite3.connect(self.db_path)
  def tearDown(self):
    self.db.close()
    remove(self.db_path)
  def test_clear_database(self):
    member_db = MemberDatabase(self.db, "?", "member_table_v")
    member_db.create()
    member_db.add(b'test_data', "John Smith", "jsmith@hackrva.org", datetime.now() + timedelta(days = 1))
    member_db.add(b'othe_data', "Crystal Meth", "cmeth@hackrva.org", datetime.now() + timedelta(days = 1))
    member_db.clear()
    self.assertEqual(len(member_db.list()), 0)

class TestMemberDatabaseMimic(unittest.TestCase):
  db_path1 = "/tmp/test_member_database_mimic1.db"
  db_path2 = "/tmp/test_member_database_mimic2.db"
  def setUp(self):
    self.db1 = sqlite3.connect(self.db_path1)
    self.db2 = sqlite3.connect(self.db_path2)
  def tearDown(self):
    self.db1.close()
    self.db2.close()
    remove(self.db_path1)
    remove(self.db_path2)
  def test_mimic_database(self):
    member_db1 = MemberDatabase(self.db1, "?", "member_table_v")
    member_db1.create()
    member_db1.add(b'test_data', "John Smith", "jsmith@hackrva.org", datetime.now() + timedelta(days = 1))
    member_db1.add(b'othe_data', "Crystal Meth", "cmeth@hackrva.org", datetime.now() + timedelta(days = 1))
    self.db1.commit()
    #
    member_db2 = MemberDatabase(self.db2, "?", "member_table_v")
    member_db2.create()
    member_db2.mimic(member_db1)
    self.assertEqual(len(member_db2.list()), 2)

class TestMemberDatabaseSync(unittest.TestCase):
  db_path1 = "/tmp/test_member_database_sync1.db"
  db_path2 = "/tmp/test_member_database_sync2.db"
  def setUp(self):
    self.db1 = sqlite3.connect(self.db_path1)
    self.db2 = sqlite3.connect(self.db_path2)
  def tearDown(self):
    self.db1.close()
    self.db2.close()
    remove(self.db_path1)
    remove(self.db_path2)
  def test_mimic_database(self):
    member_db1 = MemberDatabase(self.db1, "?", "member_table_v")
    member_db1.create()
    member_db1.add(b'test_data', "John Smith", "jsmith@hackrva.org", datetime.now() + timedelta(days = 1))
    member_db1.add(b'othe_data', "Crystal Meth", "cmeth@hackrva.org", datetime.now() + timedelta(days = 1))
    self.db1.commit()
    #
    member_db2 = MemberDatabase(self.db2, "?", "member_table_v")
    member_db2.create()
    member_db2.mimic(member_db1)
    self.assertTrue(member_db2.have_current(b'test_data'))
    self.assertTrue(member_db2.have_current(b'othe_data'))

class TestMemberDatabaseUseResource(unittest.TestCase):
  db_path1 = "/tmp/test_member_database_use_resource1.db"
  db_path2 = "/tmp/test_member_database_use_resource2.db"
  db_path3 = "/tmp/test_member_database_use_resource3.db"
  def setUp(self):
    self.db1 = sqlite3.connect(self.db_path1)
    self.db2 = sqlite3.connect(self.db_path2)
    self.db3 = sqlite3.connect(self.db_path3)
  def tearDown(self):
    self.db1.close()
    self.db2.close()
    self.db3.close()
    remove(self.db_path1)
    remove(self.db_path2)
    remove(self.db_path3)
  def test_use_resource(self):
    cur = self.db1.cursor()
    cur.execute("""
      CREATE TABLE member_table_v (
        hash CHAR(24),
        name TEXT,
        email VARCHAR(254),
        expiration_date DATE,
        resource VARCHAR(255));
    """)
    cur.execute("""
      INSERT INTO member_table_v (hash, name, email, expiration_date, resource) VALUES 
        (
          ?,
          'John Smith',
          'jsmith@hackrva.org',
          ?,
          'door'
        ),
        (
          ?,
          'John Smith',
          'jsmith@hackrva.org',
          ?,
          'laser'
        ),
        (
          ?,
          'Crystal Meth',
          'cmeth@hackrva.org',
          ?,
          'door'
        );
    """,
      (
        MemberDatabase.hash(b'test_data'), 
        datetime.now() + timedelta(days = 1),
        MemberDatabase.hash(b'test_data'), 
        datetime.now() + timedelta(days = 1),
        MemberDatabase.hash(b'othe_data'), 
        datetime.now() + timedelta(days = 1),
      ))
    self.db1.commit()
    # Use laser resource
    member_db1a = MemberDatabase(self.db1, "?", "member_table_v")
    member_db1a.use_resource('laser')
    member_db2 = MemberDatabase(self.db2, "?", "member_table_v")
    member_db2.create()
    member_db2.mimic(member_db1a)
    self.assertTrue(member_db2.have_current(b'test_data'))
    self.assertFalse(member_db2.have_current(b'othe_data'))
    # Use door resource
    member_db1b = MemberDatabase(self.db1, "?", "member_table_v")
    member_db1b.use_resource('door')
    member_db3 = MemberDatabase(self.db3, "?", "member_table_v")
    member_db3.create()
    member_db3.mimic(member_db1b)
    self.assertTrue(member_db3.have_current(b'test_data'))
    self.assertTrue(member_db3.have_current(b'othe_data'))

if __name__ == '__main__':
  unittest.main()

