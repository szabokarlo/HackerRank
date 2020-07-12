FROM php:7.4-alpine

RUN apk add --no-cache --virtual .build-deps $PHPIZE_DEPS \
    && apk add libressl-dev \
    && pecl install xdebug-2.8.0 \
    && docker-php-ext-enable xdebug \
    && apk del -f .build-deps

RUN echo "xdebug.var_display_max_depth=10" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini