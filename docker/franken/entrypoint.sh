#!/usr/bin/env sh
set -eu

cd /app

# Ensure Laravel cache paths exist (Blade compiled views, sessions, etc.)
mkdir -p \
  storage \
  bootstrap/cache \
  storage/framework/views \
  storage/framework/cache \
  storage/framework/sessions

# Ensure writable for www-data
chown -R www-data:www-data storage bootstrap/cache 2>/dev/null || true
chmod -R ug+rw storage bootstrap/cache 2>/dev/null || true

exec "$@"
