#!/usr/bin/python3
import RPIO
import serial
import mysql.connector
from mysql.connector import errorcode
import sqlite3
import time
from functools import partial


# remote_db = MySQLdb.connect(
#   host = db_path,
#   user = db_user,
#   db = db_name,
#   passwd = db_pass)
# local_db = sqlite3.connect(db_path)

class DoorConnection(object):
  """
  Contains the functionalities required for the Raspberry Pi, to access the
  user database.

  Currently, just works with the MemberDatabase class to update the local 
  database if a member is missing.
  """
  def __init__(self, local_member_db, remote_member_db):
    self.local = local_member_db
    self.remote = remote_member_db
  def update(self):
    """Updates the local database to match the remote database"""
    self.local.mimic(self.remote)
  def door_check(self, remote_db, card_data):
    try:
      # Check the local database first
      if self.local.have_current(card_data):
        # found locally
        return True
      elif self.remote.alive() and self.remote.have_current(card_data):
        # found remotely, sync 
        self.local.sync(local_cursor, card_data)
        self.local.commit()
        return True
      else:
        # reject
        return False
    except mysql.connector.errors.OperationalError as e:
      self.remote.recover()
      pass
    except mysql.connector.errors.DatabaseError as e:
      if e.errno == errorcode.CR_SERVER_GONE_ERROR or e.errno == errorcode.:
      # Attempt to recover
      self.remote.recover()
      return False
  def recover(self):
    """Allows replacing the remote database connection in case it goes away"""
    self.remote.cmd_reset_connection()
