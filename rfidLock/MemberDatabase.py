# This is mostly a CRUD package for accessing the database of users
# There are certain

import hashlib
from contextlib import closing

# Used to abstract database details a little bit more
class MemberDatabase(object):
  """
  An object used to abstract details of the member database from direct access.

  Internally, email addresses and hashes must be unique when added to the 
  database.

  The RFID data is hashed before use to prevent abuse
  """
  def __init__(self, db, subs):
    """
    db - database object to use
    subs - substitution expression for the database in use
    """
    self.db = db
    self.start_query = """
      CREATE TABLE member_table (
        hash BLOB(32),
        name TEXT,
        email TEXT,
        revoked BOOLEAN DEFAULT 0,
        CONSTRAINT pk_hash PRIMARY KEY(hash),
        CONSTRAINT unique_email UNIQUE(email));
      """
    self.destroy_query = """
      DROP TABLE member_table;
      """
    self.add_query = """
      INSERT INTO member_table (name, email, hash) VALUES ({0}, {0}, {0});
      """.format(subs)
    self.revoke_query = """
      UPDATE member_table SET revoked=1 WHERE email={0};
      """.format(subs)
    self.reinstate_query = """
      UPDATE member_table SET revoked=0 WHERE email={0};
      """.format(subs)
    self.have_query = """
      SELECT COUNT(hash) FROM member_table WHERE hash={0};
      """.format(subs)
    self.have_current_query = """
      SELECT COUNT(hash) FROM member_table WHERE hash={0} AND revoked=0;
      """.format(subs)
    self.list_query = """
      SELECT name, email, revoked FROM member_table;
      """
    self.content_query = """
      SELECT hash, name, email, revoked FROM member_table;
      """
    self.clone_query = """
      INSERT INTO member_table (hash, name, email, revoked) VALUES ({0}, {0}, {0}, {0});
      """.format(subs)
  def hash(self, card_data):
    """Hashes the provided RFID data using MD5"""
    m = hashlib.md5()
    m.update(card_data)
    return m.digest()
  def add(self, card_data, member_name, member_email):
    """Adds a new member to the list of members"""
    with closing(self.db.cursor()) as cur:
      cur.execute(self.add_query, (member_name, member_email, self.hash(card_data)))
  def revoke(self, email):
    """Marks a member as no longer a current member of the space."""
    with closing(self.db.cursor()) as cur:
      cur.execute(self.revoke_query, (email, ))
      return cur.rowcount > 0
  def reinstate(self, email):
    """Marks a former member as a current member of the space again."""
    with closing(self.db.cursor()) as cur:
      cur.execute(self.reinstate_query, (email, ))
  def have(self, card_data):
    """
    Uses the hash of the member's RFID data to check whether they have ever
    been a member.
    """
    with closing(self.db.cursor()) as cur:
      cur.execute(self.have_query, (self.hash(card_data), ))
      return cur.fetchone()[0] > 0
  def have_current(self, card_data):
    """
    Uses the member's RFID data to check whether they are a current member.
    """
    with closing(self.db.cursor()) as cur:
      cur.execute(self.have_current_query, (self.hash(card_data), ))
      return cur.fetchone()[0] > 0
  def list(self):
    """Retrieves a list of all members and former members"""
    with closing(self.db.cursor()) as cur:
      cur.execute(self.list_query)
      return cur.fetchall()
  def create(self):
    """Creates the tables necessary for the membership system"""
    with closing(self.db.cursor()) as cur:
      cur.execute(self.start_query)
  def destroy(self):
    """Removes the tables created for this system"""
    with closing(self.db.cursor()) as cur:
      cur.execute(self.destroy_query)
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

