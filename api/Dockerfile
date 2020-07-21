FROM php:7.4.4-fpm

RUN apt-get update && apt-get install -y \
    libzip-dev \
    libcurl4-openssl-dev \
    pkg-config \
    libssl-dev \
    unzip && \
    docker-php-ext-install \
    zip

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" && \
    php composer-setup.php --install-dir=/usr/local/bin --filename=composer && \
    rm composer-setup.php

RUN composer global require laravel/installer

ENV PATH="~/.composer/vendor/bin:${PATH}"

RUN pecl install -f xdebug && \
    echo "zend_extension=$(find /usr/local/lib/php/extensions/ -name xdebug.so)" > /usr/local/etc/php/conf.d/xdebug.ini;

RUN pecl install mongodb && \
    echo "extension=mongodb.so" > /usr/local/etc/php/conf.d/mongo.ini

# Remove Cache
RUN rm -rf /var/cache/apk/*

# Add UID '1000' to www-data
RUN usermod -u 1000 www-data

# Copy existing application directory permissions
# COPY --chown=www-data:www-data . /var/www/warpg

# Change current user to www
USER www-data

# Expose port 9000 and start php-fpm server
EXPOSE 9000