docker-compose run --rm api /bin/bash -c "cp .env.example .env && composer install && php artisan db:wipe && php artisan db:seed"
docker-compose run --rm app npm i