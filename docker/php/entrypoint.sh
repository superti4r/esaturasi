#!/bin/sh
set -e

dockerize -wait tcp://mysql:3306 -wait tcp://redis:6379 -timeout 60s

ROLE=$1

if [ "$ROLE" = "app" ]; then
    php artisan config:cache
    php artisan route:cache
    php artisan view:cache
    exec docker-php-entrypoint php-fpm
elif [ "$ROLE" = "queue" ]; then
    exec docker-php-entrypoint php artisan queue:work --sleep=3 --tries=3 --timeout=90
elif [ "$ROLE" = "scheduler" ]; then
    while true; do
        php artisan schedule:run --verbose --no-interaction
        sleep 60
    done
else
    exec docker-php-entrypoint "$@"
fi
