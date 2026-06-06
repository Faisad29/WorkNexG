# WorkNexG — Deployment Guide

## Quick Start (Local Development)

```bash
# 1. Clone and install dependencies
git clone <repo>
cd WorkNexG
composer install

# 2. Configure environment
cp .env.example .env
php artisan key:generate

# 3. Set up database (SQLite by default)
touch database/database.sqlite
php artisan migrate --seed

# 4. Link storage
php artisan storage:link

# 5. Install frontend assets
npm install
npm run build

# 6. Start server
php artisan serve
```

**Demo credentials** (after `--seed`):
| Role | Email | Password |
|------|-------|----------|
| Admin | admin@worknexg.test | password |
| Supervisor | supervisor@worknexg.test | password |
| Employee | employee@worknexg.test | password |

---

## Production Deployment

### Prerequisites
- PHP 8.2+
- PostgreSQL 14+
- Redis 6+
- Nginx / Apache
- Node.js 18+ (for build only)
- Supervisor (for queue workers)

### Environment Configuration

```env
APP_ENV=production
APP_DEBUG=false
APP_KEY=<generated>
APP_URL=https://app.worknexg.com

DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=worknexg
DB_USERNAME=worknexg_user
DB_PASSWORD=<secure_password>

CACHE_STORE=redis
QUEUE_CONNECTION=redis
REDIS_HOST=127.0.0.1
REDIS_PORT=6379

MAIL_MAILER=smtp
MAIL_HOST=smtp.mailgun.org
MAIL_PORT=587
MAIL_USERNAME=<key>
MAIL_PASSWORD=<secret>
MAIL_FROM_ADDRESS=noreply@worknexg.com

SANCTUM_STATEFUL_DOMAINS=app.worknexg.com
```

### Deployment Steps

```bash
composer install --optimize-autoloader --no-dev
php artisan key:generate --force
php artisan migrate --force
php artisan db:seed --force  # only first deploy
php artisan storage:link
php artisan config:cache
php artisan route:cache
php artisan view:cache
npm ci && npm run build
```

### Queue Worker (Supervisor)

```ini
[program:worknexg-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/worknexg/artisan queue:work redis --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=/var/log/worknexg-worker.log
```

### Scheduler (Cron)

```cron
* * * * * cd /var/www/worknexg && php artisan schedule:run >> /dev/null 2>&1
```

### Nginx Configuration

```nginx
server {
    listen 443 ssl http2;
    server_name app.worknexg.com;
    root /var/www/worknexg/public;
    index index.php;

    ssl_certificate /etc/ssl/certs/worknexg.crt;
    ssl_certificate_key /etc/ssl/private/worknexg.key;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

### Docker Compose (Development)

```yaml
# docker-compose.yml is included in the project root
docker-compose up -d
docker-compose exec app php artisan migrate --seed
```

---

## Health Check

```
GET /up      → Laravel health check
GET /health  → WorkNexG custom health endpoint
```

---

## Production Readiness Checklist

- [ ] `APP_DEBUG=false`
- [ ] `APP_ENV=production`
- [ ] Strong `APP_KEY` set
- [ ] PostgreSQL configured (not SQLite)
- [ ] Redis configured for cache + queues
- [ ] Queue worker running via Supervisor
- [ ] Scheduler cron installed
- [ ] HTTPS configured
- [ ] Mail provider configured (not `log`)
- [ ] `php artisan config:cache` run
- [ ] `php artisan route:cache` run
- [ ] `php artisan view:cache` run
- [ ] Storage symlink created
- [ ] Log rotation configured
- [ ] Backup strategy for DB in place
