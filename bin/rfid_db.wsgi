#!/usr/bin/python3

from cgi import parse_qs
from sys import exc_info, exc_clear
import mysql.connector
from rfidLock import MemberDatabase
from jinja2 import FileSystemLoader, Envrionment

class MemberManager(object):
  def __init__(self, template_dir, db):
    self.loader = FileSystemLoader(template_dir)
    self.env = Environment(loader=self.loader)
    self.db = db
  def respond_ok(self, start_response, content):
    response_headers = [
      ('Content-type: text/html')
    ]
    start_response('200 OK')
    return content
  def respond_fail(self, start_response, content):
    response_headers = [
      ('Content-type: text/html')
    ]
    start_response('500 Server Error')
    return content
  def manage(self, args, start_response):
    manager_html = self.env.get_template('manage.html')
    return self.respond_ok([manager_html.render(member_list=self.db.list())])
  def add(self, args, start_response):
    if args["rfid"] != args["rerfid"]:
      args["message"] = "RFID data did not match"
    else:
      self.db.add(args["rfid"], args["name"], args["email"])
      args["message"] = "\"{0}\" added as a member".format(args["email"])
      # TODO, also send a slack invitation
    return self.manage_members(args, start_response)
  def revoke(self, args, start_response):
    email = args["email"]
    self.db.revoke(email)
    # TODO also suspend from slack
    args["message"] = "\"{0}\" suspended as a member".format(email)
    return self.manage_members(args, start_response)
  def reinstate(self, args, start_response):
    email = args["email"]
    # TODO unsuspend from slack
    self.db.reinstate(email)
    args["message"] = "\"{0}\" reinstated as a member".format(email)
    return self.manage_members(args, start_response)
  def __call__(self, environ, start_response):
    args = parse_qs(environ['QUERY_STRING'])
    if not "cmd" in args:
      args["cmd"] = 'manage'
    cmds = {
      'manage': self.manage,
      'add': self.add,
      'revoke': self.revoke,
      'reinstate': self.reinstate
    }
    try:
      return cmds[args["cmd"]](args, start_response)
    except:
      exctype, excvalue = exc_info()
      exc_clear()
      except_html = self.env.get_template('except.html')
      return self.respond_ok(
        start_response,
        [except_html.render(exception=str(excvalue))])

def main():
  global application
  db = None
  config = None
  with open("config/config.json") as config_file:
    config = json.load(config_file))
    db = MemberDatabase(mysql.connector.connect(**config, "%s"))
  return MemberManager(config['templates'], db)

application = main()

