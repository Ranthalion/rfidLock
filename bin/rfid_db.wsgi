#!/usr/bin/python3

from cgi import parse_qs
from sys import exc_info
import mysql.connector
from rfidLock import MemberDatabase
from jinja2 import PackageLoader, Environment
import json

class MemberManager(object):
  def __init__(self, db):
    self.loader = PackageLoader('rfidLock', 'templates')
    self.env = Environment(loader=self.loader)
    self.db = db
  def respond_ok(self, start_response, content):
    response_headers = [
      ('Content-type', 'text/html')
    ]
    start_response('200 OK', response_headers)
    return [entry.encode() for entry in content]
  def respond_fail(self, start_response, content):
    response_headers = [
      ('Content-type', 'text/html')
    ]
    start_response('500 Server Error', response_headers)
    return [entry.encode() for entry in content]
  def manage(self, args, start_response):
    manager_html = self.env.get_template('manage.html')
    if 'message' in args:
      message = args['message'][0]
    else:
      message = ''
    member_list = [(name, email, "reinstate" if revoked else "revoke") for name, email, revoked in self.db.list()]
    return self.respond_ok(
      start_response, 
      [manager_html.render(member_list=member_list, message=message)])
  def add(self, args, start_response):
    # TODO Is the rfid data guaranteed to be text or could it be arbitrary bytes?
    if args['rfid'][0] != args['rerfid'][0]:
      args['message'] = ['RFID data did not match']
    else:
      self.db.add(args["rfid"][0].encode(), args['name'][0], args['email'][0])
      args["message"] = ["\"{0}\" added as a member".format(args['email'][0])]
      # TODO, also send a slack invitation
    return self.manage(args, start_response)
  def revoke(self, args, start_response):
    email = args["email"][0]
    self.db.revoke(email)
    # TODO also suspend from slack
    args["message"] = ["\"{0}\" suspended as a member".format(email)]
    return self.manage(args, start_response)
  def reinstate(self, args, start_response):
    email = args["email"][0]
    # TODO unsuspend from slack
    self.db.reinstate(email)
    args["message"] = ["\"{0}\" reinstated as a member".format(email)]
    return self.manage(args, start_response)
  def __call__(self, environ, start_response):
    args = parse_qs(environ['QUERY_STRING'])
    if 'cmd' in args:
      cmd = args['cmd'][0]
    else:
      cmd = 'manage'
    cmds = {
      'manage': self.manage,
      'add': self.add,
      'revoke': self.revoke,
      'reinstate': self.reinstate
    }
    return cmds[cmd](args, start_response)
#     try:
#       return cmds[args["cmd"]](args, start_response)
#     except:
#       exctype, excvalue, exctrace = exc_info()
#       except_html = self.env.get_template('except.html')
#       return self.respond_ok(
#         start_response,
#         [except_html.render(exception=str(excvalue))])

def main():
  db = None
  config_path = "/etc/rfidlock/config.json"
  config = None
  with open(config_path) as config_file:
    config = json.load(config_file)
    db = MemberDatabase(mysql.connector.connect(**config['database']), "%s")
  return MemberManager(config['templates'], db)

application = main()

