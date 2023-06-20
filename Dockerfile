# Obraz bazowy dla serwera WWW
FROM php:apache

# Instalacja rozszerzenia mysqli
RUN docker-php-ext-install mysqli

COPY php.ini /usr/local/etc/php/php.ini
COPY php.ini /usr/local/etc/php/conf.d/php.ini

RUN service apache2 restart