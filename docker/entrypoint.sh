#!/bin/sh
set -e

echo "=== Starting LexVanguard Law Firm OS 2027 ==="

# Wait for database if configured
if [ "$DB_CONNECTION" = "mysql" ] || [ "$DB_CONNECTION" = "mariadb" ]; then
    echo "Waiting for Database ($DB_HOST:$DB_PORT) to be ready..."
    until nc -z -v -w30 "$DB_HOST" "$DB_PORT"; do
        echo "Database is unavailable - waiting 2 seconds..."
        sleep 2
    done
    echo "Database is ready!"
fi

# Ensure storage directories exist
mkdir -p storage/framework/cache/data storage/framework/sessions storage/framework/views storage/logs bootstrap/cache
chmod -R 777 storage bootstrap/cache /tmp

# Generate app key if needed
if [ -z "$APP_KEY" ]; then
    php artisan key:generate --force
fi

# Run migrations and seeders
echo "Running migrations and legal demo seeders..."
php artisan migrate --force
php artisan db:seed --force

# Link storage
php artisan storage:link || true

# Cache Filament components and assets for optimal performance
php artisan filament:upgrade || true
php artisan view:clear
php artisan config:clear

echo "=== System Ready. Starting PHP-FPM ==="
exec php-fpm -F
