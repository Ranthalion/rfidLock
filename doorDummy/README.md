# Door Dummy
Door Dummy is a test environment that should mimic the interface that an rfid reader has.

## Health Check
`/health` is an endpoint that the server can hit frequently.
The server will provide a url parameter 
e.g. `/health?hash=<hash of the accessList of this resource>`
The device should compare that hash with it's internal accessList.
If we match, we will respond with 
```json
{
    success: ok
}
```

If the hash doesn't match, the server should attempt to push an update.

If the server can't push the update it will report an error.

If the server doesn't receive a health check, the server should report the error.

## TODO
- add an api key for the update endpoint