# ---- Build Stage ----
FROM php:8.4-fpm AS base

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git curl zip unzip libpng-dev libonig-dev libxml2-dev libzip-dev libicu-dev \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd intl zip \
    && pecl install redis xdebug-3.4.7 \
    && docker-php-ext-enable redis xdebug \
    && rm -rf /var/lib/apt/lists/*

# Enable Opcache for better performance
RUN docker-php-ext-install opcache

COPY ./php.ini /usr/local/etc/php/conf.d/app.ini
COPY ./xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini

# Install Composer globally
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

RUN usermod -u 1000 www-data \
    && groupmod -g 1000 www-data

USER www-data

# ---- Final Stage ----
FROM base AS production
ENV APP_ENV=production
ENV APP_DEBUG=false
CMD ["php-fpm"]
