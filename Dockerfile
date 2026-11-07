FROM php:8.2-apache

# Install necessary PHP extensions for database (PDO MySQL)
RUN docker-php-ext-install pdo pdo_mysql

# Copy all project files into Apache's web directory
COPY . /var/www/html/

# Set proper permissions
RUN chown -R www-data:www-data /var/www/html

EXPOSE 80