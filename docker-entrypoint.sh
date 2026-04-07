#!/bin/sh
set -e

# Wait for database connection
echo "Checking database connection..."
MAX_TRIES=60
TRIES=0
until php artisan tinker --execute="DB::connection()->getPdo();" > /dev/null 2>&1 || [ $TRIES -eq $MAX_TRIES ]; do
  echo "Database is unavailable - sleeping (Attempt $((TRIES+1))/$MAX_TRIES)..."
  TRIES=$((TRIES+1))
  sleep 1
done

if [ $TRIES -eq $MAX_TRIES ]; then
  echo "Could not connect to database after $MAX_TRIES attempts. Exiting."
  exit 1
fi

echo "Database is ready!"

# Ensure local public storage paths exist and are writable for uploads
mkdir -p storage/app/public/shops/logos storage/app/public/shops/heroes bootstrap/cache
if [ "$(id -u)" = "0" ]; then
  chown -R www-data:www-data storage bootstrap/cache
fi
chmod -R ug+rwX storage bootstrap/cache

# Run migrations if database is ready
php artisan migrate --force

# Storage & cache (requires .env to be present at runtime)
php artisan storage:link --force
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Start Apache
exec apache2-foreground
