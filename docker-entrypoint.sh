#!/bin/bash

# Wait for database to be ready
echo "Waiting for MySQL to be ready..."
while ! nc -z db 3306; do
  sleep 1
done
echo "MySQL is ready!"

# Run migrations
echo "Running migrations..."
php artisan migrate --force

# Seed database if needed
# php artisan db:seed --force

# Clear caches
php artisan optimize:clear

# Start PHP-FPM
exec php-fpm
