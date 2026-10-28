# Production Deployment Guide

## Docker Deployment

The repository includes a production-ready `Dockerfile` (multi-stage build) and `docker-compose.yml`.

### Quick Docker Setup
```bash
# 1. Start containers in detached mode
docker compose up -d --build

# 2. Run migrations and database seeds inside the container
docker compose exec app php artisan migrate --force
docker compose exec app php artisan db:seed --force

# 3. Access the application
# Frontend / Marketing: http://localhost:8080
# Operations Admin: http://localhost:8080/admin
# Super Admin: http://localhost:8080/platform
# Finance: http://localhost:8080/finance
```

### Containers in Docker Compose
- **app**: PHP 8.4-FPM with OPcache, BCMath, GD, Redis, Intl extensions.
- **web**: Nginx reverse proxy routing static assets and PHP requests.
- **db**: MySQL 8.0 with persistent storage volume.
- **redis**: Redis 7 for cache, sessions, and asynchronous background queues.
- **queue**: Dedicated Laravel queue worker processing asynchronous jobs.
- **scheduler**: Automated cron daemon executing `php artisan schedule:run` every minute.

## Traditional Linux (Ubuntu / Debian / Nginx) Deployment
1. Ensure PHP 8.2+, Nginx, MySQL, and Redis are installed.
2. Clone repository to `/var/www/returns-refund-manager`.
3. Set permissions:
   ```bash
   sudo chown -R www-data:www-data /var/www/returns-refund-manager/storage /var/www/returns-refund-manager/bootstrap/cache
   sudo chmod -R 775 /var/www/returns-refund-manager/storage /var/www/returns-refund-manager/bootstrap/cache
   ```
4. Configure Supervisor for queue workers:
   ```ini
   [program:rma-worker]
   process_name=%(program_name)s_%(process_num)02d
   command=php /var/www/returns-refund-manager/artisan queue:work redis --sleep=3 --tries=3
   autostart=true
   autorestart=true
   user=www-data
   numprocs=4
   redirect_stderr=true
   stdout_logfile=/var/www/returns-refund-manager/storage/logs/worker.log
   ```
5. Configure cron for the scheduler:
   ```cron
   * * * * * cd /var/www/returns-refund-manager && php artisan schedule:run >> /dev/null 2>&1
   ```
