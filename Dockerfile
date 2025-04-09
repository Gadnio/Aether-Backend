# Use the official PHP Apache base image
FROM php:8.2-apache

# Set the working directory
WORKDIR /var/www/html

# Copy the current project files into the container
COPY . .

# Install project dependencies using Composer
RUN apt-get update && \
    apt-get install -y unzip && \
    curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer && \
    composer install --no-dev --optimize-autoloader

# Ensure Apache listens on port 8080
RUN sed -i 's/Listen 80/Listen 8080/' /etc/apache2/ports.conf && \
    sed -i 's/<VirtualHost \*:80>/<VirtualHost *:8080>/' /etc/apache2/sites-available/000-default.conf

# Enable necessary Apache modules
RUN a2enmod rewrite

# Expose port 8080 to the outside
EXPOSE 8080

# Start Apache in the foreground
CMD ["apache2-foreground"]
