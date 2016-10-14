#!/usr/bin/python3

from cgi import parse_qs
import mysql.connector
from rfidLock import MemberDatabase

def redirect(destination, start_response):
  # Redirects to static pages
  response_headers = [
    ('Location', destination)
  ]
  start_response('303 See Other', response_headers)
  return []

def list_members(db, args, start_response):
  # Get the list of members
  table_html =
    "<table>" + 
    "".join(["<tr><td>{0}</td><td>{1}</td><td>{2}</td></tr>".format(*data) 
      for data in db.list()]) + "</table>"
  response_headers = [
    ('Content-type: text/html')
  ]
  start_response('200 OK', response_headers)
  with open("list_members.html") as list_html:
    return [table_html if line == "<!-- LIST -->" else line for line in readlines(list_html)]

def add_member(db, args, start_response):
  if args["rfid"] != args["rerfid"]:
    return redirect('rfid_no_match.html')
  else:
    db.add(args["rfid"], args["name"], args["email"])
    # TODO, also send a slack invitation
    return redirect('added_member.html')

def revoke_member(db, args, start_response):
  db.revoke(args["email"])
  return redirect('revoke_member.html')

def reinstate_member(db, args, start_response):
  db.reinstate(args["email"])
  return redirect('reinstate_member.html')

# WSGI application
def application(environ, start_response):
  try:
    db = None
    with open("db_config.json") as db_config:
      db = MemberDatabase(mysql.connector.connect(**json.load(db_config)), "%s")
    args = parse_qs(environ['QUERY_STRING'])
    # This is much like a switch-case statement but is a bit easier 
    # to read in my opinion.
    cmds = {
      "list": list_members,
      "add": add_member,
      "revoke": revoke_member,
      "reinstate": reinstate_member
    }
    return cmds[args["cmd"]](db, args, start_response)
  except:
    # TODO write the failure to a log file
    return redirect('server_failure.html')

