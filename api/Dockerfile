FROM php:7.4.4-fpm

RUN apt-get update && apt-get install -y \
    libzip-dev \
    libcurl4-openssl-dev \
    pkg-config \
    libssl-dev \
    unzip && \
    docker-php-ext-install \
    zip && \
    php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" && \
    php composer-setup.php --install-dir=/usr/local/bin --filename=composer && \
    rm composer-setup.php && \
    composer global require laravel/installer && \
    pecl install -f xdebug && \
    echo "zend_extension=$(find /usr/local/lib/php/extensions/ -name xdebug.so)" > /usr/local/etc/php/conf.d/xdebug.ini && \
    pecl install mongodb && \
    echo "extension=mongodb.so" > /usr/local/etc/php/conf.d/mongo.ini && \
    usermod -u 1000 www-data && \
    rm -rf /var/cache/apk/*

ENV PATH="~/.composer/vendor/bin:${PATH}"

USER www-data

EXPOSE 9000