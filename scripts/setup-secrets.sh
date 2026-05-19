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

if [ ! -f .env ]; then
    cp .env.example .env
fi

log_warn "This script will interactively prompt for sensitive values."
log_warn "Your input will NOT be shown on screen for security."
echo ""

generate_app_key() {
    log_info "Generating APP_KEY..."
    
    if command -v php &> /dev/null; then
        APP_KEY=$(php -r "echo 'base64:' . base64_encode(random_bytes(32));")
        log_info "Generated: ${APP_KEY:0:20}..."
        echo "APP_KEY=$APP_KEY"
    else
        log_warn "PHP not found. Generate APP_KEY manually:"
        echo "  php artisan key:generate --show"
    fi
}

prompt_secret() {
    local var_name=$1
    local var_description=$2
    local default_value=${3:-}
    
    while true; do
        read -sp "Enter $var_description ($var_name): " value
        echo ""
        
        if [ -z "$value" ] && [ -n "$default_value" ]; then
            value="$default_value"
        fi
        
        if [ -z "$value" ]; then
            log_error "Value cannot be empty"
            continue
        fi
        
        read -sp "Confirm $var_description: " confirm
        echo ""
        
        if [ "$value" = "$confirm" ]; then
            echo "$value"
            return 0
        else
            log_error "Values do not match, please try again"
        fi
    done
}

echo "========================================="
echo "  E-Saturasi Secrets Setup"
echo "========================================="
echo ""

APP_KEY=$(generate_app_key)
DB_PASSWORD=$(prompt_secret "DB_PASSWORD" "Database password" "")
DB_ROOT_PASSWORD=$(prompt_secret "DB_ROOT_PASSWORD" "MySQL root password" "")
GRAFANA_ADMIN_PASSWORD=$(prompt_secret "GRAFANA_ADMIN_PASSWORD" "Grafana admin password" "")

log_info "Updating .env file..."

sed -i "s/^APP_KEY=.*/APP_KEY=$APP_KEY/" .env
sed -i "s/^DB_PASSWORD=.*/DB_PASSWORD=$DB_PASSWORD/" .env
sed -i "s/^DB_ROOT_PASSWORD=.*/DB_ROOT_PASSWORD=$DB_ROOT_PASSWORD/" .env
sed -i "s/^GRAFANA_ADMIN_PASSWORD=.*/GRAFANA_ADMIN_PASSWORD=$GRAFANA_ADMIN_PASSWORD/" .env

chmod 600 .env

log_info "Secrets configured successfully!"
log_warn "Make sure to:"
echo "  1. Keep .env file secure (chmod 600)"
echo "  2. Do NOT commit .env to git"
echo "  3. Backup .env file safely"
echo "  4. Use GitHub Secrets for CI/CD"
