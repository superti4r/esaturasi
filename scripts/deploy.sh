#!/bin/bash

set -e

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
PROJECT_DIR="$(dirname "$SCRIPT_DIR")"

RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m'

log_info() {
    echo -e "${GREEN}✓${NC} $1"
}

log_warn() {
    echo -e "${YELLOW}⚠${NC} $1"
}

log_error() {
    echo -e "${RED}✗${NC} $1"
}

cd "$PROJECT_DIR"

log_info "Starting deployment..."

if [ ! -f .env ]; then
    log_error ".env file not found"
    echo "Please create .env file with required secrets"
    exit 1
fi

verify_secrets() {
    local required_vars=("APP_KEY" "DB_PASSWORD" "DB_ROOT_PASSWORD" "GRAFANA_ADMIN_PASSWORD")
    local missing_vars=()
    
    for var in "${required_vars[@]}"; do
        if ! grep -q "^${var}=" .env || [ -z "$(grep "^${var}=" .env | cut -d '=' -f2)" ]; then
            missing_vars+=("$var")
        fi
    done
    
    if [ ${#missing_vars[@]} -gt 0 ]; then
        log_error "Missing required secrets in .env:"
        for var in "${missing_vars[@]}"; do
            echo "  - $var"
        done
        exit 1
    fi
    
    log_info "All required secrets are configured"
}

verify_secrets

log_info "Building Docker images..."
docker-compose build

log_info "Starting services..."
docker-compose up -d

log_warn "Waiting for database to be ready..."
sleep 10

log_info "Running database migrations..."
docker-compose exec -T app php artisan migrate --force

log_info "Generating Filament Shield permissions..."
docker-compose exec -T app php artisan shield:generate --all

if ! docker-compose exec -T app php artisan tinker <<'TINKEREOF' 2>/dev/null | grep -q "admin"; 
  use Spatie\Permission\Models\Role;
  echo Role::where('name', 'super_admin')->exists() ? "admin" : "none";
  exit(0);
TINKEREOF
then
  log_info "Setting up Super Admin user..."
  docker-compose exec -T app php artisan shield:super-admin --user=1 --panel=m
fi

log_info "Caching configuration..."
docker-compose exec -T app php artisan config:cache
docker-compose exec -T app php artisan route:cache
docker-compose exec -T app php artisan view:cache

log_info "Creating storage link..."
docker-compose exec -T app php artisan storage:link || true

log_info "Checking service health..."
docker-compose ps

log_info "Deployment completed successfully!"
log_warn "Don't forget to:"
echo "  1. Update APP_URL in .env to your domain"
echo "  2. Setup SSL/TLS with Certbot or reverse proxy"
echo "  3. Configure backups"
echo "  4. Monitor logs: docker-compose logs -f"
