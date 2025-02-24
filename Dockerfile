FROM php:8.2-cli-alpine

COPY . /var/www/html

WORKDIR /var/www/html

RUN apk update && apk upgrade \
    # && apk add --no-cache \
    # && apk add --no-cache build-essential \
    && apk add --no-cache git autoconf build-base linux-headers \
    && pecl install pcov \
    && docker-php-ext-enable pcov \
    # && apk add --no-cache php-pear \
    # && apk add --no-cache autoconf \
    # && pecl channel-update pecl.php.net \
    # && apk add --no-cache autoconf build-essential\
    # && pecl install pcov \
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
