#!/bin/sh
set -e

# This entrypoint script is designed to handle different roles for the Laravel container.
# It ensures that the application is properly set up before running the main command.

# The first argument determines the container's role.
ROLE=$1

if [ "$ROLE" = "app" ]; then
    # For the 'app' role (php-fpm), wait for DB and run optimizations.
    echo "Container role: app. Waiting for database..."
    # This uses Laravel's built-in DB monitor. It's better than a simple nc or mysqladmin ping
    # because it verifies the application can actually connect and authenticate.
    until php artisan db:monitor --database=mysql --quiet; do
        >&2 echo "Database is unavailable - sleeping"
        sleep 3
    done
    >&2 echo "Database is up. Running optimizations..."

    php artisan config:cache
    php artisan route:cache
    php artisan view:cache

    # Start PHP-FPM
    exec docker-php-entrypoint php-fpm

elif [ "$ROLE" = "queue" ]; then
    # For the 'queue' role, just wait for the DB before starting the worker.
    echo "Container role: queue. Waiting for database..."
    until php artisan db:monitor --database=mysql --quiet; do
        >&2 echo "Database is unavailable - sleeping"
        sleep 3
    done
    >&2 echo "Database is up. Starting queue worker..."

    # The command for the queue worker is passed as subsequent arguments.
    exec docker-php-entrypoint php artisan queue:work --sleep=3 --tries=3 --timeout=90

elif [ "$ROLE" = "scheduler" ]; then
    # For the 'scheduler' role, wait for the DB and then start the schedule loop.
    echo "Container role: scheduler. Waiting for database..."
    until php artisan db:monitor --database=mysql --quiet; do
        >&2 echo "Database is unavailable - sleeping"
        sleep 3
    done
    >&2 echo "Database is up. Starting scheduler..."

    while true; do
        php artisan schedule:run --verbose --no-interaction
        sleep 60
    done

else
    # If no specific role is matched, just execute the given command.
    # This allows running arbitrary artisan commands.
    exec docker-php-entrypoint "$@"
fi