FROM php:8.2-apache

WORKDIR /var/www/html
COPY . .

# Let Apache listen on 8080
RUN sed -i 's/Listen 80/Listen 8080/' /etc/apache2/ports.conf && \
    sed -i 's/<VirtualHost \*:80>/<VirtualHost *:8080>/' /etc/apache2/sites-available/000-default.conf

# Enable .htaccess rewrites
RUN a2enmod rewrite && \
    echo '<Directory /var/www/html>\nAllowOverride All\n</Directory>' >> /etc/apache2/apache2.conf

# Optional: install Composer if app depends on it
RUN apt-get update && apt-get install -y unzip curl git && \
    curl -sS https://getcomposer.org/installer | php && \
    mv composer.phar /usr/local/bin/composer && \
    composer install --no-dev --optimize-autoloader || true

EXPOSE 8080
CMD ["apache2-foreground"]
