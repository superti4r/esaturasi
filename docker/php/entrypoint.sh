#!/usr/bin/env bash
set -euo pipefail

APP_DIR=/var/www/html
cd "$APP_DIR"

# If APP_KEY wasn't provided, we can't auto-generate reliably in immutable images.
# Fail fast in production-like envs.
if [[ -z "${APP_KEY:-}" ]]; then
  echo "[entrypoint] APP_KEY is empty. Provide APP_KEY via environment/secrets." >&2
fi

# Ensure storage + cache are writable (for bind-mount dev too)
mkdir -p storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache || true

# Wait for DB if configured
if [[ "${DB_CONNECTION:-}" == "mysql" && -n "${DB_HOST:-}" ]]; then
  echo "[entrypoint] Waiting for MySQL at ${DB_HOST}:${DB_PORT:-3306}..."
  for i in {1..60}; do
    if mysqladmin ping -h"${DB_HOST}" -P"${DB_PORT:-3306}" --silent >/dev/null 2>&1; then
      break
    fi
    sleep 2
  done
fi

# Optimize caches (safe in containers)
su-exec www-data php artisan config:cache || true
su-exec www-data php artisan route:cache || true
su-exec www-data php artisan view:cache || true

# If vendor autoload optimized file missing, run composer scripts and artisan discovery
if [ -f vendor/autoload.php ]; then
  echo "[entrypoint] vendor exists, ensuring autoload & discovery"
  su-exec www-data composer dump-autoload --optimize || true
  su-exec www-data php artisan package:discover --ansi || true
  # filament upgrade may require interactive; run safe upgrade if available
  su-exec www-data php artisan filament:upgrade || true
fi

# Migrate automatically for staging; for production you may want to run as a job.
if [[ "${RUN_MIGRATIONS:-false}" == "true" ]]; then
  echo "[entrypoint] Running migrations..."
  su-exec www-data php artisan migrate --force
fi

exec "$@"
