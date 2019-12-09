FROM php:7.3-apache

RUN apt update && apt install -y git zip

COPY composer.json /var/www/html/
COPY composer.lock /var/www/html/

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer && composer install

COPY . /var/www/html/
COPY .docker/vhost.conf /etc/apache2/sites-available/000-default.conf

RUN chown -R www-data:www-data /var/www/html && a2enmod rewrite

EXPOSE 80
