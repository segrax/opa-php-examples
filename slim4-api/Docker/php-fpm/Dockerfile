FROM php:7.3-fpm

RUN apt-get update && \
	docker-php-ext-install json pdo pdo_mysql sockets && \
	mv $PHP_INI_DIR/php.ini-production $PHP_INI_DIR/0-php.ini

# Development extra
RUN pecl install xdebug && docker-php-ext-enable xdebug

# Copy ini files into place
COPY php-override.ini 		$PHP_INI_DIR/conf.d/1-overide.ini
COPY php-dev-override.ini 	$PHP_INI_DIR/conf.d/2-dev-override.ini 
COPY php-fpm-override.conf 	$PHP_INI_DIR/conf.d/3-fpm-override.conf

COPY xdebug.ini /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini


