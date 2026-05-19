#!/bin/bash
set -e

echo "Waiting for database to be ready..."
while ! nc -z db 3306; do
    sleep 1
done
echo "Database is ready!"

echo "Waiting for Redis to be ready..."
while ! nc -z redis 6379; do
    sleep 1
done
echo "Redis is ready!"

if [ ! -f .env ] || ! grep -q "APP_KEY=" .env || [ -z "$(grep 'APP_KEY=' .env | cut -d '=' -f2)" ]; then
    echo "Generating Laravel application key..."
    php artisan key:generate --force
fi

echo "Running database migrations..."
php artisan migrate --force

if php artisan tinker <<'EOF' 2>/dev/null | grep -q "false\|0"
    use App\Models\User;
    echo User::count() === 0 ? "0" : "1";
    exit(0);
EOF
then
    echo "Seeding database with initial data..."
    php artisan migrate:refresh --seed --force
fi

echo "Generating Filament Shield permissions..."
php artisan shield:generate --all

if ! php artisan tinker <<'EOF' 2>/dev/null | grep -q "admin"
    use Spatie\Permission\Models\Role;
    echo Role::where('name', 'super_admin')->exists() ? "admin" : "none";
    exit(0);
EOF
then
    echo "Setting up Super Admin user..."
    php artisan shield:super-admin --user=1 --panel=admin
fi

echo "Caching configuration..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

php artisan storage:link || true

chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

echo "Starting Nginx and PHP-FPM..."
service nginx start

exec php-fpm -F