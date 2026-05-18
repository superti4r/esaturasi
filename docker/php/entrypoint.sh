#!/bin/sh
set -e

mkdir -p \
  storage/framework/cache/data \
  storage/framework/sessions \
  storage/framework/views \
  storage/logs \
  bootstrap/cache \
  public

cp -a public-dist/. public/

chown -R www-data:www-data storage bootstrap/cache public

exec docker-php-entrypoint "$@"