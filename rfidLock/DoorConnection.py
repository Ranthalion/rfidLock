#!/usr/bin/python3

# TODO need to make things a bit more database agnostic if possible
import mysql.connector
from mysql.connector import errorcode

import time
from functools import partial

# remote_db = mysql.connector.connect(
#   host = db_path,
#   user = db_user,
#   database = db_name,
#   password = db_pass)
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
  def check_request(self, card_data):
    """
    Whether the card_data's hash is in the local or remote databases

    First checks the local database, returns true if card_data's hash
    is available locally, otherwise checks the remote database 
    and returns true if the hash is available remotely, and returns false
    otherwise.
    """
    try:
      # Check the local database first
      if self.local.have_current(card_data):
        # found locally
        return True
      elif self.remote.have_current(card_data):
        # found remotely, sync 
        self.local.sync(self.remote, card_data)
        return True
      else:
        # reject
        return False
    except mysql.connector.errors.OperationalError as e:
      if e.errno == errorcode.CR_SERVER_GONE_ERROR:
        # Attempt to recover
        if self.recover():
          return self.checkRequest(card_data)
    except mysql.connector.errors.DatabaseError as e:
      return False
  def recover(self):
    """
    Allows repairing the remote database connection in case it goes away

    Returns true if the connection is successfully reestablished.
    Note that this will only work for mysql.connector connections.
    """
    self.remote.reconnect()
    return self.remote.is_connected()

