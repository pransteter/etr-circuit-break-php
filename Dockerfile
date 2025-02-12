FROM php:8.2-cli-alpine

COPY . /var/www/html

WORKDIR /var/www/html

RUN apk update && apk upgrade \
    && apk add --no-cache \
    # libzip-dev \
    # zip \
    # unzip \
    # git \
    # && docker-php-ext-install zip \
    && chmod +x ./install_composer.sh \
    && ./install_composer.sh \
    && mv ./composer.phar /usr/bin/composer \
    && composer install
