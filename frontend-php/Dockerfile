FROM php:8.2-apache

RUN a2enmod rewrite proxy proxy_http

COPY frontend-php/apache.conf /etc/apache2/sites-available/000-default.conf
COPY frontend-php/public /var/www/html
COPY frontend/public/sofia-logo-login.png /var/www/html/assets/sofia-logo-login.png

