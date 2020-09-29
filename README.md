# WARPG - Web ASCII RPG

## Features

## Screens

## Development

### Environment

* Back-end API: [PHP](https://github.com/php/php-src)/[Laravel](https://github.com/laravel/laravel)
* Database: [MongoDB](https://github.com/mongodb/mongo)
* Front-end: [React](https://github.com/facebook/react)
* Webserver: [Nginx](https://github.com/nginx/nginx)
* Testing: [PHPUnit](https://github.com/sebastianbergmann/phpunit) & [Jest](https://github.com/facebook/jest)
* [Docker](https://github.com/docker/docker-ce) containers

### Setup & Run

Clone the repository and run the following to install all dependencies and write hosts rules:

    sudo ./setup.sh

And then, to bring everything up:

    docker-compose up -d

### Testing

Back-end testing is done with:

    php artisan test

Or:

    vendor/bin/phpunit
