FROM php:8.4-fpm-alpine

# Install system dependencies and PHP extensions
RUN apk add --no-cache \
    curl \
    git \
    unzip \
    libpq \
    mysql-client \
    icu-libs \
    icu-dev \
    zlib-dev \
    && docker-php-ext-install intl zip pdo pdo_mysql \
    && apk del icu-dev zlib-dev

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Set working directory
WORKDIR /var/www/projectx

# Copy application files
COPY . .

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# Set permissions
RUN chown -R www-data:www-data /var/www/projectx

# Expose port
EXPOSE 9000

# Start PHP-FPM
CMD ["php-fpm"]
