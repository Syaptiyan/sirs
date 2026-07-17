# 12 - DEPLOYMENT

## Docker Setup

### docker-compose.yml

```yaml
version: '3.8'

services:
  app:
    build:
      context: .
      dockerfile: docker/php/Dockerfile
    container_name: sirs-app
    restart: unless-stopped
    volumes:
      - .:/var/www/html
      - ./docker/php/local.ini:/usr/local/etc/php/conf.d/local.ini
    networks:
      - sirs-network
    depends_on:
      - redis

  nginx:
    image: nginx:alpine
    container_name: sirs-nginx
    restart: unless-stopped
    ports:
      - "80:80"
      - "443:443"
    volumes:
      - .:/var/www/html
      - ./docker/nginx/conf.d:/etc/nginx/conf.d
      - ./docker/nginx/ssl:/etc/nginx/ssl
    networks:
      - sirs-network
    depends_on:
      - app

  redis:
    image: redis:alpine
    container_name: sirs-redis
    restart: unless-stopped
    ports:
      - "6379:6379"
    volumes:
      - redis-data:/data
    networks:
      - sirs-network

networks:
  sirs-network:
    driver: bridge

volumes:
  redis-data:
```

### docker/php/Dockerfile

```dockerfile
FROM php:8.3-fpm-alpine

RUN apk add --no-cache \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    oniguruma-dev \
    libxml2-dev \
    zip \
    unzip \
    git \
    curl

RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_pgsql mbstring exif pcntl bcmath gd opcache

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /var/www/html

COPY . .

RUN composer install --optimize-autoloader --no-dev

RUN chown -R www-data:www-data /var/www/html/writable

CMD ["php-fpm"]
```

### docker/nginx/conf.d/app.conf

```nginx
server {
    listen 80;
    server_name sirs.example.com;
    return 301 https://$server_name$request_uri;
}

server {
    listen 443 ssl http2;
    server_name sirs.example.com;

    root /var/www/html/public;
    index index.php;

    ssl_certificate /etc/nginx/ssl/fullchain.pem;
    ssl_certificate_key /etc/nginx/ssl/privkey.pem;
    ssl_protocols TLSv1.2 TLSv1.3;
    ssl_ciphers HIGH:!aNULL:!MD5;

    add_header X-Frame-Options "SAMEORIGIN" always;
    add_header X-Content-Type-Options "nosniff" always;
    add_header X-XSS-Protection "1; mode=block" always;
    add_header Strict-Transport-Security "max-age=31536000; includeSubDomains" always;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass app:9000;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_hide_header X-Powered-By;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }

    client_max_body_size 50M;
}
```

---

## Environment Configuration

### .env (Production)

```env
CI_ENVIRONMENT=production

app.baseURL=https://sirs.example.com
app.indexPage=

database.default.hostname=aws-0-ap-southeast-1.pooler.supabase.com
database.default.database=postgres
database.default.username=postgres.xxxx
database.default.password=******
database.default.DBDriver=Postgre
database.default.port=5432
database.default.DBDebug=false

session.driver=database
session.cookieSecure=true
session.cookieSameSite=Strict

cache.handler=redis
cache.redis.host=redis
cache.redis.port=6379
```

---

## Deployment Steps

### Initial Deployment

```bash
# 1. Clone repository
git clone https://github.com/org/sirs.git
cd sirs

# 2. Copy environment file
cp .env.example .env

# 3. Edit .env untuk production
nano .env

# 4. Build Docker images
docker-compose build

# 5. Start containers
docker-compose up -d

# 6. Run migrations
docker-compose exec app php spark migrate

# 7. Run seeders
docker-compose exec app php spark db:seed

# 8. Set permissions
docker-compose exec app chown -R www-data:www-data writable

# 9. Build frontend assets
npm install
npm run build
```

### Update Deployment

```bash
# 1. Pull latest changes
git pull origin main

# 2. Install dependencies
docker-compose exec app composer install --optimize-autoloader --no-dev

# 3. Run migrations
docker-compose exec app php spark migrate

# 4. Clear cache
docker-compose exec app php spark cache:clear

# 5. Rebuild frontend
npm install
npm run build

# 6. Restart containers
docker-compose restart
```

---

## SSL/TLS Setup

### Let's Encrypt with Certbot

```bash
# Install certbot
apt install certbot python3-certbot-nginx

# Obtain certificate
certbot --nginx -d sirs.example.com

# Auto-renewal
crontab -e
0 0 1 * * certbot renew --quiet
```

---

## Backup Strategy

### Database Backup

```bash
#!/bin/bash
# scripts/backup-db.sh

DATE=$(date +%Y%m%d_%H%M%S)
BACKUP_DIR="/backups/database"
RETENTION_DAYS=30

# Create backup
pg_dump -h $DB_HOST -U $DB_USER -d $DB_NAME > $BACKUP_DIR/sirs_$DATE.sql

# Compress
gzip $BACKUP_DIR/sirs_$DATE.sql

# Upload to cloud storage (optional)
# aws s3 cp $BACKUP_DIR/sirs_$DATE.sql.gz s3://backups/

# Cleanup old backups
find $BACKUP_DIR -name "*.gz" -mtime +$RETENTION_DAYS -delete
```

### File Backup

```bash
#!/bin/bash
# scripts/backup-files.sh

DATE=$(date +%Y%m%d_%H%M%S)
BACKUP_DIR="/backups/files"

# Backup writable directory
tar -czf $BACKUP_DIR/writable_$DATE.tar.gz writable/

# Upload to cloud storage
# aws s3 cp $BACKUP_DIR/writable_$DATE.tar.gz s3://backups/
```

### Cron Jobs

```bash
# Database backup daily at 2 AM
0 2 * * * /var/www/html/scripts/backup-db.sh

# File backup weekly at 3 AM
0 3 * * 0 /var/www/html/scripts/backup-files.sh

# Log rotation
0 0 * * * find /var/www/html/writable/logs -name "*.log" -mtime +30 -delete
```

---

## Monitoring

### Health Check Endpoint

```php
// app/Controllers/HealthController.php
public function check()
{
    $checks = [
        'status' => 'ok',
        'timestamp' => date('c'),
        'version' => config('App')->version,
        'database' => $this->checkDatabase(),
        'cache' => $this->checkCache(),
        'storage' => $this->checkStorage(),
    ];

    $healthy = collect($checks)->every(fn($v) => $v === 'ok' || is_array($v) && $v['status'] === 'ok');

    return $this->response->setJSON($checks)->setStatusCode($healthy ? 200 : 503);
}
```

### Docker Health Check

```yaml
# docker-compose.yml
services:
  app:
    healthcheck:
      test: ["CMD", "curl", "-f", "http://localhost/health"]
      interval: 30s
      timeout: 10s
      retries: 3
      start_period: 40s
```

### Log Monitoring

```bash
# View logs
docker-compose logs -f app
docker-compose logs -f nginx

# Log rotation
# /etc/logrotate.d/sirs
/var/www/html/writable/logs/*.log {
    daily
    missingok
    rotate 14
    compress
    delaycompress
    notifempty
    create 0640 www-data www-data
}
```

---

## Performance Optimization

### PHP OPcache

```ini
; docker/php/local.ini
opcache.enable=1
opcache.memory_consumption=256
opcache.interned_strings_buffer=16
opcache.max_accelerated_files=20000
opcache.validate_timestamps=0
opcache.save_comments=1
opcache.fast_shutdown=1
```

### Nginx Caching

```nginx
# Static files caching
location ~* \.(jpg|jpeg|png|gif|ico|css|js|woff2)$ {
    expires 1y;
    add_header Cache-Control "public, immutable";
}
```

### Application Caching

```php
// Cache master data
cache()->save('polyclinics', $polyclinics, 3600);

// Cache dashboard stats
cache()->save('dashboard_stats_' . $userId, $stats, 120);
```

---

## Scaling

### Horizontal Scaling

```yaml
# docker-compose.yml
services:
  app:
    deploy:
      replicas: 3
    
  nginx:
    depends_on:
      - app
```

### Load Balancer

```nginx
upstream sirs_backend {
    least_conn;
    server app1:9000;
    server app2:9000;
    server app3:9000;
}
```

---

## Maintenance Mode

```bash
# Enable maintenance
touch writable/.maintenance

# Disable maintenance
rm writable/.maintenance
```

```php
// app/Config/App.php
public bool $maintenanceMode = false;

// Check in middleware
if (file_exists(WRITEPATH . '/.maintenance')) {
    return view('errors/maintenance');
}
```
