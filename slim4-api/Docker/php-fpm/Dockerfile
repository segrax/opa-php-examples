FROM php:7.3-fpm

RUN apt-get update 

RUN docker-php-ext-install json pdo pdo_mysql sockets

RUN mv $PHP_INI_DIR/php.ini-production $PHP_INI_DIR/php.ini

COPY php-override.ini $PHP_INI_DIR/conf.d/0-php.ini
COPY php-fpm-override.conf $PHP_INI_DIR/conf.d/php-fpm-override.conf


# Development extras
RUN pecl install xdebug && docker-php-ext-enable xdebug
COPY xdebug.ini /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

COPY php-dev-override.ini $PHP_INI_DIR/conf.d/1-override.ini 
