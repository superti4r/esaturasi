#!/bin/bash

set -euo pipefail

readonly APP_PATH="${1:?ERROR: APP_PATH required}"
readonly REPO_URL="${2:?ERROR: REPO_URL required}"
readonly BRANCH="${3:?ERROR: BRANCH required}"

for var in APP_KEY APP_URL DB_PASSWORD DB_ROOT_PASSWORD GRAFANA_ADMIN_PASSWORD MAIL_USERNAME MAIL_PASSWORD; do
  [ -n "${!var:-}" ] || { echo "ERROR: $var not set"; exit 1; }
done

echo "Starting deployment..."
mkdir -p "$APP_PATH"
cd "$APP_PATH"

if [ ! -d ".git" ]; then
  git clone "$REPO_URL" .
else
  git fetch origin --prune
fi

git checkout "$BRANCH"
git pull origin "$BRANCH"

echo "Repository: $(git rev-parse --short HEAD)"

cp .env.example .env

sed -i "s|^APP_KEY=.*|APP_KEY=$APP_KEY|" .env
sed -i "s|^APP_URL=.*|APP_URL=$APP_URL|" .env
sed -i "s|^APP_ENV=.*|APP_ENV=production|" .env
sed -i "s|^APP_DEBUG=.*|APP_DEBUG=false|" .env
sed -i "s|^DB_PASSWORD=.*|DB_PASSWORD=$DB_PASSWORD|" .env
sed -i "s|^DB_ROOT_PASSWORD=.*|DB_ROOT_PASSWORD=$DB_ROOT_PASSWORD|" .env
sed -i "s|^GRAFANA_ADMIN_PASSWORD=.*|GRAFANA_ADMIN_PASSWORD=$GRAFANA_ADMIN_PASSWORD|" .env
sed -i "s|^MAIL_USERNAME=.*|MAIL_USERNAME=$MAIL_USERNAME|" .env
sed -i "s|^MAIL_PASSWORD=.*|MAIL_PASSWORD=$MAIL_PASSWORD|" .env

docker compose config > /dev/null || { echo "ERROR: Invalid docker-compose.yml"; exit 1; }

docker compose down --remove-orphans 2>/dev/null || true

echo "Building Docker images..."
docker compose build --no-cache --progress=plain

echo "Starting containers..."
docker compose up -d

docker image prune -f > /dev/null || true

echo "Deployment completed successfully"
docker compose ps
