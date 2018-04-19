#!/usr/bin/env bash

# From Creating a Vagrant Box
echo "Running additional steps for membership provisioning in dev"
apt-get install php7.0-oauth
echo "extension=oauth.so" >> /etc/php/7.0/fpm/php.ini
cd membership
echo "Running Migrate"
php artisan migrate
echo "Seeding"
php artisan db:seed
echo "Completed provisioning"
