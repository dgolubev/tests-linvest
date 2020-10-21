FROM php:latest

LABEL maintainer="d.e.golubev@gmail.com"

WORKDIR /app

EXPOSE 9000

RUN apt-get update \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug \
    && rm -rf /tmp/*

COPY php.ini $PHP_INI_DIR/conf.d/99-custom.ini
