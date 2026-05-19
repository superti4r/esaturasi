# E-Saturasi - E-Learning Platform

Sistem E-Learning terintegrasi dengan teknologi modern.

## Requirements

- Docker 29.5.1+
- Docker Compose 5.1.3+
- Docker Buildx 0.34.0+ (untuk production builds)

## Features

✅ Automated Database Seeding (User, Permissions)
✅ Filament Shield Role-Based Access Control
✅ Multi-platform Docker Builds
✅ CI/CD via GitHub Actions
✅ VPS Auto-Deployment
✅ Prometheus & Grafana Monitoring

## Quick Start

### 1. Setup Environment Secrets

```bash
./scripts/setup-secrets.sh
```

### 2. Build & Deploy

```bash
docker-compose build
docker-compose up -d
```

### 3. Access Application

- **App**: http://localhost
- **Admin**: http://localhost/admin (User: admin@esaturasi.local, Pass: admin123.)
- **Grafana**: http://localhost:3000 (admin/admin123)
- **Prometheus**: http://localhost:9090

## Database & User Setup

### First-time Setup
- Database migrations run automatically
- Default admin user seeded: **Bachtiar Dwi Pramudi** (admin@esaturasi.local : admin123.)
- Filament Shield permissions generated
- Super Admin role assigned to user ID 1

### Seeders
- `UserSeeder` - Creates default admin user
- `ArchiveSeeder` - Archive data
- `MajorClassroomSeeder` - Program & classroom data

Run manually:
```bash
docker-compose exec -T app php artisan migrate --seed --force
```

## Shield Commands

### Generate Permissions (runs on every deployment)
```bash
docker-compose exec -T app php artisan shield:generate --all
```

### Setup Super Admin (runs once on first deployment)
```bash
docker-compose exec -T app php artisan shield:super-admin --user=1 --panel=admin
```

## Documentation

- [Deployment Guide](DEPLOYMENT_GUIDE.md) - Complete setup, CI/CD, seeding, troubleshooting
- [Docker Versions](docker/VERSIONS.md) - Version compatibility & requirements

## Project Structure

```
esaturasi/
├── app/                 # Laravel application code
├── docker/             # Docker configuration
│   ├── Dockerfile
│   ├── docker-compose.yml
│   ├── entrypoint.sh
│   ├── nginx/
│   └── services/
├── scripts/            # Deployment & setup scripts
│   ├── deploy.sh       # Production deployment
│   └── setup-secrets.sh # Interactive secrets setup
├── .github/workflows/  # GitHub Actions
│   ├── deploy.yml      # Auto deployment
│   └── security.yml    # Security scanning
├── .env.example        # Environment template
└── docker-compose.yml  # Main compose file
```

## Key Features

✅ PHP 8.4 with Laravel  
✅ MySQL 8.0 Database  
✅ Redis Caching & Queues  
✅ Nginx Web Server  
✅ Prometheus & Grafana Monitoring  
✅ Docker Multi-stage Builds  
✅ GitHub Actions CI/CD  
✅ Secure Secrets Management  

## Security

- **Secrets**: Never hardcoded, managed via GitHub Secrets or setup script
- **Database**: Encrypted passwords, isolated network
- **HTTP Headers**: Security headers configured in Nginx
- **Docker**: Non-root user, minimal image size, vulnerability scanning
- **Secrets Scanning**: Automated detection of exposed credentials

## Environment Variables

All secrets are managed securely:

| Variable | Setup Method | Example |
|---|---|---|
| APP_KEY | Generated | `base64:xxxxx` |
| DB_PASSWORD | GitHub Secret | `P@ssw0rd123!` |
| DB_ROOT_PASSWORD | GitHub Secret | `RootPass456!` |
| GRAFANA_ADMIN_PASSWORD | GitHub Secret | `GrafanaPass789!` |
| Other credentials | GitHub Secret | - |

See [scripts/README.md](scripts/README.md) for details.

## Deployment Options

### Option 1: GitHub Actions (Recommended)
```bash
# Add secrets to GitHub Settings
# Push to main/production branch
git push origin main
# GitHub Actions automatically deploys
```

### Option 2: Manual Deployment
```bash
./scripts/deploy.sh
```

### Option 3: Docker Hub Registry
```bash
# Add DOCKER_USERNAME and DOCKER_PASSWORD as secrets
# Images automatically pushed to Docker Hub
```

## Monitoring

Access Grafana dashboard:
```
http://localhost:3000
Username: admin
Password: (set in GRAFANA_ADMIN_PASSWORD)
```

Available metrics:
- CPU Usage
- Memory Usage
- Disk Usage
- Network I/O
- Application Performance

## Troubleshooting

### Database connection failed
```bash
docker-compose logs db
docker-compose restart db
```

### Secrets not loaded in GitHub Actions
1. Check secret names (case-sensitive)
2. Verify repository has access to secrets
3. Re-save secrets after update

### Deployment failed
```bash
docker-compose logs -f
./scripts/deploy.sh  # Run again with verbose output
```

## Best Practices

✅ Use `setup-secrets.sh` for initial setup  
✅ Rotate secrets regularly  
✅ Never commit .env to git  
✅ Keep .env permissions as 600  
✅ Use GitHub Secrets for production  
✅ Monitor logs regularly  
✅ Backup database regularly  

## Support & Documentation

- [Docker Guide](docker/README.md) - Container & deployment configuration
- [GitHub Secrets Guide](GITHUB_SECRETS.md) - Secret management & CI/CD
- [Scripts Guide](scripts/README.md) - Deployment scripts reference

## License

Proprietary - All rights reserved
