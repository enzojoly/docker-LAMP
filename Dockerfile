FROM php:8.3-apache

# Install mysqli and pdo_mysql
RUN docker-php-ext-install mysqli pdo pdo_mysql
