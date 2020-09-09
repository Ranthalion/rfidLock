## Install docker
https://docs.docker.com/engine/install/

## Install docker-compose
https://docs.docker.com/compose/install/

## Standup the docker containers
```
sh buildandrun
```
This should run the docker containers in the background.
You can check that they are running by executing the following command.
```
docker ps
```

## Seed the db and make a test user
```
sh seedDev.sh
```
This will add the appropriate tables to the DB and create a test user.

```
username: test
email: test@test.com
password: test
```

app should be running on http://localhost/
