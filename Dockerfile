# Use the PHP 8.2 Apache base image
FROM php:8.2-apache

# Install necessary PHP extensions
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Copy the custom virtual host config (if you have one)
COPY ./src/vhost.conf /etc/apache2/sites-available/000-default.conf

# Set the working directory
WORKDIR /var/www/html

# Copy project files to container
COPY ./src /var/www/html

# Set permissions for the web server user (optional)
RUN chown -R www-data:www-data /var/www/html

# Expose port 80 for web traffic
EXPOSE 80

# Restart Apache to apply changes (Not required; use CMD instead)
CMD ["apache2-foreground"]
