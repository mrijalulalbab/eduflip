FROM php:8.2-apache

# Install System Dependencies
RUN apt-get update && apt-get install -y \
    libzip-dev \
    zip \
    unzip \
    && docker-php-ext-install pdo pdo_mysql mysqli zip

# Enable Apache Mod Rewrite
RUN a2enmod rewrite

# Set Working Directory
WORKDIR /var/www/html

# Copy Source Code
COPY . /var/www/html/

# Adjust Apache DocumentRoot to point to web/public
ENV APACHE_DOCUMENT_ROOT /var/www/html/web/public

RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Set Permissions
RUN chown -R www-data:www-data /var/www/html
