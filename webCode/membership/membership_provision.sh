#!/usr/bin/env bash

# From Creating a Vagrant Box
echo "Running additional steps for membership provisioning in dev"
cd membership
echo "Running Migrate"
php artisan migrate
echo "Seeding"
php artisan db:seed
echo "Completed provisioning"
