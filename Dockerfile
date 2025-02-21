FROM php:8.2-cli-alpine

COPY . /var/www/html

WORKDIR /var/www/html

RUN apk update && apk upgrade \
    && apk add --no-cache \
    && apk add --no-cache git \
    # && apk add --no-cache autoconf \
    # && pecl channel-update pecl.php.net \
    && apk add --no-cache autoconf \
    && pecl install xdebug \
    # && pecl install xdebug --configure-options '--with-php-config=/usr/bin/php-config' \
    # libzip-dev \
    # zip \
    # unzip \
    # git \
    # && docker-php-ext-install zip \
    && chmod +x ./install_composer.sh \
    && ./install_composer.sh \
    && mv ./composer.phar /usr/bin/composer \
    && composer install
