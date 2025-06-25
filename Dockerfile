FROM php:8.3-apache

# Install mysqli
RUN docker-php-ext-install mysqli

# Copy source code to Apache directory
COPY . /var/www/html/

# Enable Apache rewrite module (optional kung SPA ka)
RUN a2enmod rewrite

EXPOSE 80
