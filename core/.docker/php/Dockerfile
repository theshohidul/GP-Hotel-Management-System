FROM php:7.4-fpm
RUN apt-get update && apt-get install -y libpq-dev git 
RUN apt-get -y install zip unzip
RUN docker-php-ext-install pdo pdo_pgsql pgsql
RUN ln -s /usr/local/etc/php/php.ini-production /usr/local/etc/php/php.ini
RUN sed -i -e 's/;extension=pgsql/extension=pgsql/' /usr/local/etc/php/php.ini
RUN sed -i -e 's/;extension=pdo_pgsql/extension=pdo_pgsql/' /usr/local/etc/php/php.ini

WORKDIR /var/www/html

RUN curl -sS https://getcomposer.org/installer | \
php -- --install-dir=/usr/bin/ --filename=composer --version=2.0.8


RUN pecl install redis \
&& docker-php-ext-enable redis


ENV COMPOSER_ALLOW_SUPERUSER=1
