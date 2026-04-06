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

# Run migrations if database is ready
php artisan migrate --force

# Start Apache
exec apache2-foreground
