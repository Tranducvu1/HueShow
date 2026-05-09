FROM php:8.1-apache

# Cài đặt MySQL extension
RUN docker-php-ext-install mysqli

# Cài đặt PDO MySQL (optional nhưng tốt)
RUN docker-php-ext-install pdo pdo_mysql

# Enable Apache modules
RUN a2enmod rewrite

# Copy code vào container
COPY . /var/www/html/

# Set working directory
WORKDIR /var/www/html

# Set permissions
RUN chown -R www-data:www-data /var/www/html

EXPOSE 80

CMD ["apache2-foreground"]