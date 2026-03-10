#!/bin/sh

# Ensure SQLite database exists
mkdir -p /var/www/html/storage/app
touch /var/www/html/storage/app/precious_hairmpire.sqlite
chown -R www-data:www-data /var/www/html/storage

# Optimization
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Run migrations
php artisan migrate --force

# Start Apache in the foreground
exec apache2-foreground