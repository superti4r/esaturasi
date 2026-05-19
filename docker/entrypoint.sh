#!/bin/bash
set -e

echo "Waiting for database to be ready..."
for i in {1..30}; do
    if nc -z db 3306 2>/dev/null; then
        echo "Database is ready!"
        break
    fi
    echo "Attempt $i: Waiting for database..."
    sleep 2
done

echo "Waiting for Redis to be ready..."
for i in {1..30}; do
    if nc -z redis 6379 2>/dev/null; then
        echo "Redis is ready!"
        break
    fi
    echo "Attempt $i: Waiting for Redis..."
    sleep 2
done

echo "Setting up application..."

if [ ! -f .env ] || ! grep -q "APP_KEY=" .env || [ -z "$(grep 'APP_KEY=' .env | cut -d '=' -f2)" ]; then
    echo "Generating Laravel application key..."
    php artisan key:generate --force
fi

echo "Running Composer post-autoload-dump scripts..."
composer dump-autoload --optimize --no-ansi 2>/dev/null || true

echo "Running database migrations..."
php artisan migrate --force

echo "Checking if database needs seeding..."
USER_COUNT=$(php artisan tinker --execute="echo App\Models\User::count();" 2>/dev/null || echo "0")

if [ "$USER_COUNT" = "0" ] || [ -z "$USER_COUNT" ]; then
    echo "Seeding database with initial data..."
    php artisan migrate:refresh --seed --force
fi

echo "Generating Filament Shield permissions..."
php artisan shield:generate --all

echo "Checking if Super Admin exists..."
SUPER_ADMIN=$(php artisan tinker --execute="echo Spatie\Permission\Models\Role::where('name', 'super_admin')->exists() ? 'yes' : 'no';" 2>/dev/null || echo "no")

if [ "$SUPER_ADMIN" != "yes" ]; then
    echo "Setting up Super Admin user..."
    php artisan shield:super-admin --user=1 --panel=admin
fi

echo "Caching configuration..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "Creating storage link..."
php artisan storage:link || true

echo "Setting proper permissions..."
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

echo "Starting Nginx and PHP-FPM..."
service nginx start

exec php-fpm -F