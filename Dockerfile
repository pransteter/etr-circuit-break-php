FROM php:8.2-cli-alpine

COPY . /var/www/html

WORKDIR /var/www/html

RUN apk update && apk upgrade \
    && apk add --no-cache git autoconf build-base linux-headers \
    && php -m | grep -q pcov || pecl install pcov \
    && docker-php-ext-enable pcov \
    && chmod +x ./install_composer.sh \
    && ./install_composer.sh \
    && mv ./composer.phar /usr/bin/composer \
    && composer install
