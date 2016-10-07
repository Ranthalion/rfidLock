# This is mostly a CRUD package for accessing the database of users
# There are certain

import hashlib

# Used to abstract database details a little bit more
class MemberDatabase(object):
  """
  An object used to abstract details of the member database from direct access.

  Internally, email addresses and hashes must be unique when added to the 
  database.

  The RFID data is hashed before use to prevent abuse
  """
  def __init__(self, db, subs):
    self.cur = db.cursor()
    self.start_query = """
      CREATE TABLE member_table (
        id INT NOT NULL AUTO_INCREMENT,
        name TEXT,
        email TEXT,
        hash BLOB(32),
        revoked BOOLEAN DEFAULT FALSE,
        CONSTRAINT pk_id PRIMARY KEY(id),
        CONSTRAINT unique_email UNIQUE(email),
        CONSTRAINT unique_hash UNIQUE(hash));
      """
    self.destroy_query = """
      DROP TABLE member_table;
      """
    self.add_query = """
      INSERT INTO member_table (name, email, hash) VALUES ({0}, {0}, {0});
      """.format(subs)
    self.revoke_query = """
      UPDATE member_table SET revoked=TRUE WHERE email={0};
      """.format(subs)
    self.revoked_query = """
      SELECT id FROM member_table WHERE revoked=TRUE;
      """
    self.reinstate_query = """
      UPDATE member_table SET revoked=FALSE WHERE email={0};
      """.format(subs)
    self.have_query = """
      SELECT id FROM member_table WHERE hash={0};
      """.format(subs)
    self.have_current_query = """
      SELECT id FROM member_table WHERE hash={0} AND revoked=FALSE;
      """.format(subs)
    self.list_query = """
      SELECT name, email, revoked FROM member_table;
      """
    self.content_query = """
      SELECT id, name, email, hash, revoked FROM member_table;
      """
    self.clone_query = """
      INSERT INTO member_table (id, name, email, hash, revoked) VALUES {0};
      """.format(subs)
  def __del__(self):
    self.cur.close()
  def hash(self, card_data):
    """Hashes the provided RFID data using MD5"""
    m = hashlib.md5()
    m.update(card_data.encode())
    return m.digest()
  def add(self, card_data, member_name, member_email):
    """Adds a new member to the list of members"""
    self.cur.execute(self.add_query, (member_name, member_email, self.hash(card_data)))
  def revoke(self, email):
    """Marks a member as no longer a current member of the space."""
    self.cur.execute(self.revoke_query, (email, ))
    return cur.rowcount > 0
  def revoked(self):
    """Retrieves the table ids of the revoked members"""
    self.cur.execute(self.revoked_query)
    return self.cur.fetchall()
  def reinstate(self, email):
    """Marks a former member as a current member of the space again."""
    self.cur.execute(self.reinstate_query, (email, ))
  def have(self, card_data):
    """
    Uses the hash of the member's RFID data to check whether they have ever
    been a member.
    """
    self.cur.execute(self.have_query, (self.hash(card_data), ))
    return cur.rowcount > 0
  def have_current(self, card_data):
    """
    Uses the member's RFID data to check whether they are a current member.
    """
    self.cur.execute(self.have_current_query, (self.hash(card_data), ))
    return cur.rowcount > 0
  def list(self):
    """Retrieves a list of all members and former members"""
    self.cur.execute(self.list_query)
    return cur.fetchall()
  def start(self):
    """Creates the tables necessary for the membership system"""
    self.cur.execute(self.start_query)
  def destroy(self):
    """Removes the tables created for this system"""
    self.cur.execute(self.destroy_query)
  def clear(self):
    """Resets the contents of this database to be empty"""
    self.destroy()
    self.start()
  def mimic(self, other):
    """Makes this database identical to the provided database"""
    self.clear()
    other.cur.execute(other.content_query)
    self.cur.execute(self.clone_query, other.cur.fetchall())

