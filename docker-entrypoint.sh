#!/bin/bash
set -e

php artisan config:clear

# Migrate FIRST to create tables
php artisan migrate --force

# Then clear cache (table now exists)
php artisan cache:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan db:seed --force

apache2-foreground