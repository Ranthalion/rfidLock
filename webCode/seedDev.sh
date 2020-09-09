#!/bin/sh

# set App Encryption Key
docker exec -it app bash -c "php artisan key:generate"

# provision db
docker exec -it app bash -c "bash membership_provision.sh && echo \"DB::table('users')->insert(['name'=>'testuser','email'=>'test@test.com','password'=>Hash::make('test')])\"  | php artisan tinker"

# show the test creds and url
echo "\n\nhttp://localhost/login  \n\nemail: test@test.com\npassword: test\n"