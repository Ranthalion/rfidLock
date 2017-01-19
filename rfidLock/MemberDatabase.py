# This is mostly a CRUD package for accessing the database of users
# There are certain

import hashlib
from base64 import b64encode
from contextlib import closing
from datetime import datetime

# Used to abstract database details a little bit more
class MemberDatabase(object):
  """
  An object used to abstract details of the member database from direct access.

  Internally, email addresses and hashes must be unique when added to the 
  database.

  The RFID data is hashed before use to prevent abuse
  """
  def __init__(self, db, subs, table_name = "member_table"):
    """
    db - database object to use
    subs - substitution expression for the database in use
    """
    self.db = db
    self.subs = subs
    self.table_name = table_name

    try:
      self.db.autocommit(True)
    except:
      pass

    # Technically, emails may be 254 characters at most
    self.start_query = u"""
      CREATE TABLE {1} (
        hash CHAR(24),
        name TEXT,
        email VARCHAR(254),
        expire_date DATE,
        CONSTRAINT pk_hash PRIMARY KEY(hash));
      """.format(subs, table_name)
    self.destroy_query = u"""
      DROP TABLE {1};
      """.format(subs, table_name)
    self.add_query = u"""
      INSERT INTO {1} (name, email, hash, expire_date) VALUES ({0}, {0}, {0}, {0});
      """.format(subs, table_name)
    self.have_query = u"""
      SELECT COUNT(hash) FROM {1} WHERE hash={0};
      """.format(subs, table_name)
    self.have_current_query = u"""
      SELECT COUNT(hash) FROM {1} WHERE hash={0} AND expire_date > {0};
      """.format(subs, table_name)
    self.list_query = u"""
      SELECT name, email, expire_date FROM {1};
      """.format(subs, table_name)
    self.content_query = u"""
      SELECT hash, name, email, expire_date FROM {1};
      """.format(subs, table_name)
    self.record_query = u"""
      SELECT hash, name, email, expire_date FROM {1} WHERE hash={0};
      """.format(subs, table_name)
    self.clone_query = u"""
      INSERT INTO {1} (hash, name, email, expire_date) VALUES ({0}, {0}, {0}, {0});
      """.format(subs, table_name)
  @staticmethod
  def hash(card_data):
    """Hashes the provided RFID data using MD5"""
    m = hashlib.md5()
    m.update(card_data)
    # Needs to go through this for Python2 support
    # Binary data is hard to work with across versions
    print b64encode(m.digest()).decode()
    return b64encode(m.digest()).decode()
  def use_resource(self, resource):
    self.have_query = u"""
      SELECT COUNT(hash) FROM {1} WHERE hash={0} AND resource={2};
      """.format(self.subs, self.table_name, resource)
    self.have_current_query = u"""
      SELECT COUNT(hash) FROM {1} WHERE hash={0} AND expire_date > {0} AND resource='{2}';
      """.format(self.subs, self.table_name, resource)
    self.list_query = u"""
      SELECT name, email, expire_date FROM {1} WHERE resource='{2}';
      """.format(self.subs, self.table_name, resource)
    self.content_query = u"""
      SELECT hash, name, email, expire_date FROM {1} WHERE resource='{2}';
      """.format(self.subs, self.table_name, resource)
    self.record_query = u"""
      SELECT hash, name, email, expire_date FROM {1} WHERE hash={0} AND resource='{2}';
      """.format(self.subs, self.table_name, resource)
  def add(self, card_data, member_name, member_email, expiration):
    """Adds a new member to the list of members"""
    with closing(self.db.cursor()) as cur:
      cur.execute(self.add_query, (member_name, member_email, MemberDatabase.hash(card_data), expiration))
    self.db.commit()
  def have(self, card_data):
    """
    Uses the hash of the member's RFID data to check whether they have ever
    been a member.
    """
    with closing(self.db.cursor()) as cur:
      cur.execute(self.have_query, (MemberDatabase.hash(card_data), ))
      result = cur.fetchone()[0]
      self.db.commit()
      print result
      return result > 0
  def have_current(self, card_data):
    """
    Uses the member's RFID data to check whether they are a current member.
    """
    with closing(self.db.cursor()) as cur:
      cur.execute(self.have_current_query, (MemberDatabase.hash(card_data), datetime.now()))
      result = cur.fetchone()[0]
      self.db.commit()
      return result > 0
  def list(self):
    """Retrieves a list of all members and former members"""
    with closing(self.db.cursor()) as cur:
      cur.execute(self.list_query)
      return cur.fetchall()
  def create(self):
    """Creates the tables necessary for the membership system"""
    with closing(self.db.cursor()) as cur:
      cur.execute(self.start_query)
    self.db.commit()
  def destroy(self):
    """Removes the tables created for this system"""
    with closing(self.db.cursor()) as cur:
      cur.execute(self.destroy_query)
    self.db.commit()
  def clear(self):
    """Resets the contents of this database to be empty"""
    self.destroy()
    self.create()
  def mimic(self, other):
    """Makes this database identical to the provided database"""
    self.clear()
    with closing(self.db.cursor()) as cur, closing(other.db.cursor()) as othercur:
      othercur.execute(other.content_query)
      for entry in othercur:
        cur.execute(self.clone_query, entry)
    self.db.commit()
  def sync(self, other, card_data):
    """Updates a singular record from a different database"""
    with closing(self.db.cursor()) as cur, closing(other.db.cursor()) as othercur:
      othercur.execute(other.record_query, (MemberDatabase.hash(card_data), ))
      cur.execute(self.clone_query, othercur.fetchone())
    self.db.commit()

