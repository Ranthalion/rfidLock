# Mostly for slack invite
import json
import pycurl
import traceback
from time import time
from sys import stderr, exc_info

res = False
msg = None

def handle_result(buf):
  global res
  global msg
  try:
    obj = json.loads(buf)
    res = obj["ok"]
    if not res:
      msg = obj["error"]
  except:
    stderr.write("Failure in write function")
    typ, val, trace = exc_info()
    stderr.write(str(val))
    traceback.print_tb(trace, None, stderr)

def invite(site, email, name, token):
  global res, msg
  # check the result
  try:
    curl = pycurl.Curl()
    url = "https://" + site + "/api/users.admin.invite?t=" + str(int(time()))
    options = ["email=" + email, "token=" + token, "first_name=" + name]
    curl.setopt(pycurl.URL, url)
    curl.setopt(pycurl.POST, True)
    curl.setopt(pycurl.COPYPOSTFIELDS, "&".join(options))
    curl.setopt(pycurl.WRITEFUNCTION, handle_result)
    curl.perform() # perform blocks execution
    result = res
    res = False
    message = msg
    msg = None
    curl.close()
    return (result, message)
  except:
    typ, val, trace = exc_info()
    stderr.write(str(typ))
    stderr.write("Failure\r\n")
    stderr.write(str(val))
    traceback.print_tb(trace, None, stderr)

