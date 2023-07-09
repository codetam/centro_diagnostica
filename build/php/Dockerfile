FROM php:8.1-apache

RUN apt-get update && \
    apt-get install -y zlib1g-dev libpng-dev git libzip-dev zip

RUN docker-php-ext-install mysqli pdo pdo_mysql gd zip

RUN a2enmod rewrite && service apache2 restart