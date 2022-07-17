FROM php:8.0-apache

RUN apt-get update &&\
    apt-get install --no-install-recommends --assume-yes --quiet ca-certificates curl git &&\
    rm -rf /var/lib/apt/lists/*

RUN pecl install xdebug && docker-php-ext-enable xdebug
RUN touch /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
RUN touch /usr/local/etc/php/conf.d/error_reporting.ini

RUN echo 'zend_extension=xdebug' >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
RUN echo ' ' >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
RUN echo '[xdebug]' >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
RUN echo 'xdebug.mode=develop,debug' >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
RUN echo 'xdebug.client_host=host.docker.internal' >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
RUN echo 'xdebug.start_with_request=yes' >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

RUN echo 'error_reporting=E_ALL' >> /usr/local/etc/php/conf.d/error_reporting.ini

RUN apt-get update && apt-get install -y libpq-dev && docker-php-ext-install pdo pdo_pgsql