#!/bin/sh

$ENV_FILE="membership/.env"
$VENDOR_DIR="membership/vendor"

# making a .env file so that the app can store a generated key
# ... but let's not overwrite a file if it's already there
if [ -f "$ENV_FILE"  ]; then
    echo "$ENV_FILE exists."; 
else
    cp membership/.env.example membership/.env;
fi

# make the vendor folder if it doesn't exist
if [ -f "$VENDOR_DIR"  ]; then
    echo "$VENDOR_DIR exists."; 
else
    mkdir $VENDOR_DIR
fi

# build webapp
docker-compose -f docker-compose.yml up --build -d;

echo "be sure to run 'sh seedDev.sh' to seed the db";
