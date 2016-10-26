# Mostly for slack invite
import json
import pycurl
import traceback
from time import time
from sys import stderr, exc_info

class SlackInviter(object):
  def __init__(self, site, token):
    self.res = False
    self.msg = None
    self.site = site
    self.token = token
  def handle_result(self, buf):
    try:
      obj = json.load(buf)
      self.res = obj['ok']
      if not self.res:
        self.msg = obj['error']
    except:
      stderr.write("Failure in write function")
      typ, val, trace = exc_info()
      stderr.write(str(val))
      traceback.print_tb(trace, None, stderr)
  def invite(self, email, name):
    # check the result
    try:
      curl = pycurl.Curl()
      url = "https://" + self.site + "/api/users.admin.invite?t=" + str(int(time()))
      options = ["email=" + email, "token=" + self.token, "first_name=" + name]
      curl.setopt(pycurl.URL, url)
      curl.setopt(pycurl.POST, True)
      curl.setopt(pycurl.COPYPOSTFIELDS, "&".join(options))
      curl.setopt(pycurl.WRITEFUNCTION, handle_result)
      curl.perform() # perform blocks execution
      result = self.res
      self.res = False
      message = self.msg
      self.msg = None
      curl.close()
      return (result, message)
    except:
      typ, val, trace = exc_info()
      stderr.write(str(typ))
      stderr.write("Failure\r\n")
      stderr.write(str(val))
      traceback.print_tb(trace, None, stderr)

