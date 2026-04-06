#!/bin/sh
set -e

# Run migrations if database is ready
php artisan migrate --force

# Start Apache
exec apache2-foreground
