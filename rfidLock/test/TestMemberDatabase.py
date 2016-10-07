#!/usr/bin/python3

import unittest
import sqlite3
from context import rfidLock
from rfidLock import MemberDatabase
import random, string

class TestMemberDatabaseHash(unittest.TestCase):
  def test_not_nop(self):
    # Check that the hash isn't a nop 
    self.assertNotEqual(MemberDatabase.hash(None, "hello"), "hello")
  def test_few_collide(self):
    # Check that hash doesn't collide with 2000 unique random strings
    self.assertEqual(len(frozenset([MemberDatabase.hash(None, ''.join(random.sample(string.ascii_lowercase + string.ascii_uppercase + string.digits, 10))) for i in range(0, 2000)])), 2000)

class TestMemberDatabaseStart(unittest.TestCase):
  pass

class TestMemberDatabaseDestroy(unittest.TestCase):
  pass

class TestMemberDatabaseClear(unittest.TestCase):
  pass

class TestMemberDatabaseMimic(unittest.TestCase):
  pass

class TestMemberDatabaseAdd(unittest.TestCase):
  pass

class TestMemberDatabaseRevoke(unittest.TestCase):
  pass

class TestMemberDatabaseRevoked(unittest.TestCase):
  pass

class TestMemberDatabaseReinstate(unittest.TestCase):
  pass

class TestMemberDatabaseHave(unittest.TestCase):
  pass

class TestMemberDatabaseHaveCurrent(unittest.TestCase):
  pass

class TestMemberDatabaseList(unittest.TestCase):
  pass

if __name__ == '__main__':
  unittest.main()

