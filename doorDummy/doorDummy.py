# flask_web/app.py
from functools import wraps
from flask import Flask, request, jsonify, abort

import hashlib

app = Flask(__name__)

# don't hardcode this on an actual device
APPKEY='test';

# rfidlist - list of rfid tags that are allowed access on this device
rfidlist=[];
currentHash="";

# currently we are joining hte list together using a hyphen as the delemiter
# then we hash with md5 for now
def hash_access_list(access_list):
    s="-"
    s = s.join(sorted(access_list))
    return hashlib.md5(str(s).encode('utf-8')).hexdigest()

# update the internal access list
def updateRFIDList(newList):
    global rfidlist
    global currentHash
    rfidlist = newList
    currentHash = hash_access_list(rfidlist)
    return currentHash


# decorator function to require an appkey
def require_appkey(view_function):
    @wraps(view_function)
    # the new, post-decoration function. Note *args and **kwargs here.
    def decorated_function(*args, **kwargs):
        global APPKEY
        if request.args.get('key') and request.args.get('key') == APPKEY:
            return view_function(*args, **kwargs)
        else:
            abort(401)
    return decorated_function

# `/update` takes a json object with property of `rfids`
# `rfids` is a list of rfids that have access to this device
# the update will completely replace what the device was storing
@app.route('/update', methods=["POST"])
@require_appkey
def update():
    global rfidlist
    global currentHash
    if request.method == 'POST':
        content = request.get_json(silent=True)
        updateRFIDList(content["rfids"])
        return jsonify({'current': currentHash})

# health check returns {'status': 'ok'} if the provided hash matches
# the internal stored hash
# this should be something that we can hit frequently
@app.route('/health')
def healthCheck():
    global currentHash
    serverHash = request.args.get('hash', '')
    if not serverHash:
        return jsonify({'status': 'error - no hash provided'})

    if serverHash == currentHash:
        return jsonify({'success': 'ok'})
    return jsonify({'status': 'error - no match'})

# remotely allow access
# should send an event to log on the server
@app.route('/grant-access')
def fake_access_attempt():
    return "NOT IMPLEMENTED: this is a fake attempt"




# DO NOT SHOW THE ACCESS LIST on an actual device 
# this is only for testing
@app.route('/peek')
def peakAccessList():
    global currentHash
    return jsonify({'current': currentHash, 'rfidlist': list(rfidlist)})

# display some information about doorDummy
@app.route('/')
def hello_world():
    return 'Door Dummy!'


if __name__ == "__main__": 
    app.run(host ='0.0.0.0', port = 5000, debug = True) 

