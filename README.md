# WARPG - Web ASCII RPG

## Features

## Screens

## Development

### Environment

* Back-end API: PHP/Laravel
* Database: MongoDB
* Front-end: React
* Webserver: Nginx
* Testing: PHP Unit & Jest
* Docker containers

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
