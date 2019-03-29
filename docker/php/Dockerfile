FROM php:7.0-fpm
MAINTAINER sean
RUN pecl install mongodb \
    && docker-php-ext-enable mongodb
EXPOSE 9000
WORKDIR /usr/share/nginx