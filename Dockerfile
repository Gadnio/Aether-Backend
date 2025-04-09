FROM php:8.2-apache

# Enable Apache mod_rewrite (used by .htaccess)
RUN a2enmod rewrite

# Copy everything to Apache's root directory
COPY . /var/www/html/

# Set working directory
WORKDIR /var/www/html

# Optional: give Apache permission
RUN chown -R www-data:www-data /var/www/html
