#!/bin/bash

# builds the images
docker-compose build

# api setup
docker-compose run --rm api /bin/bash -c "cp .env.example .env && composer install && php artisan db:wipe && php artisan db:seed"

# app setup
docker-compose run --rm app npm i

# adds addresses to hosts - needs superuser rights
echo "127.0.0.1    www.warpg.com warpg.com api.warpg.com" >> /etc/hosts

