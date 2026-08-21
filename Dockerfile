FROM php:8.4-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y --no-install-recommends \
    curl \
    git \
    unzip \
    libpq-dev \
    libzip-dev \
    libicu-dev \
    && docker-php-ext-install intl zip pdo pdo_mysql \
    && apt-get purge -y --auto-remove -o APT::AutoRemove::RecommendsImportant=false \
    && rm -rf /var/lib/apt/lists/*

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Set working directory
WORKDIR /var/www/projectx

# Copy application files
COPY . .

# Match the gift-card form limits: several images are submitted together.
RUN printf "upload_max_filesize=12M\npost_max_size=32M\nmax_file_uploads=10\nmax_execution_time=120\nmax_input_time=120\n" > /usr/local/etc/php/conf.d/uploads.ini

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# Set permissions
RUN chown -R www-data:www-data /var/www/projectx

# Expose port
EXPOSE 9000

# Start PHP-FPM
CMD ["php-fpm"]
