# Base image: PHP with Apache, official, stable for production
FROM php:8.3-apache

# Set working directory inside the container
WORKDIR /var/www/html

# Copy all project files into container's web root
COPY . /var/www/html/

# (Optional) Enable Apache modules (e.g. rewrite)
RUN a2enmod rewrite

# (Optional) Install common PHP extensions if needed, example:
# RUN docker-php-ext-install pdo pdo_mysql mysqli

# Change Apache listen port to 8080 (Render default)
RUN sed -i 's/Listen 80/Listen 8080/' /etc/apache2/ports.conf \
    && sed -i 's/:80/:8080/' /etc/apache2/sites-available/000-default.conf

# Expose port 8080 to the outside world
EXPOSE 8080

# Start Apache server in foreground (required by Render)
CMD ["apache2-foreground"]
