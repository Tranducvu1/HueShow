FROM php:8.2-fpm-alpine

WORKDIR /var/www/html

# Install required extensions
RUN docker-php-ext-install pdo pdo_mysql mysqli

# Copy PHP configuration
COPY --chown=www-data:www-data . .

# Create necessary directories
RUN mkdir -p /var/www/html && \
    chown -R www-data:www-data /var/www/html

# Start PHP-FPM
CMD ["php-fpm"]