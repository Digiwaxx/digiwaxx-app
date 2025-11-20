# Digiwaxx Production Deployment Guide

## 🚀 Complete Security Hardening Implementation

This guide provides step-by-step instructions for deploying your Digiwaxx music distribution application to production with all security fixes applied.

---

## ✅ Pre-Deployment Security Checklist

### Phase 1: Environment Configuration

- [ ] Copy `.env.example` to `.env`
- [ ] Generate new `APP_KEY`: `php artisan key:generate`
- [ ] Set `APP_ENV=production`
- [ ] Set `APP_DEBUG=false`
- [ ] Configure database credentials
- [ ] Configure mail settings
- [ ] **CRITICAL:** Add new Stripe API keys (rotate exposed keys!)
- [ ] Configure pCloud credentials
- [ ] Set session security options
- [ ] Configure file upload limits

### Phase 2: Database Setup

- [ ] Backup existing database
- [ ] Run `DATABASE_INDEXES.sql` to create performance indexes
- [ ] Verify indexes: `SHOW INDEX FROM clients;`
- [ ] Plan password migration (see `PASSWORD_MIGRATION_GUIDE.md`)
- [ ] Test database performance with EXPLAIN queries

### Phase 3: Code Deployment

- [ ] Pull latest code from `claude/music-distribution-setup-01WgR45pdEMugea6qdeUGQyR` branch
- [ ] Run `composer install --no-dev --optimize-autoloader`
- [ ] Run `php artisan config:cache`
- [ ] Run `php artisan route:cache`
- [ ] Run `php artisan view:cache`
- [ ] Set proper file permissions
- [ ] Ensure `storage/` and `bootstrap/cache/` are writable

### Phase 4: Security Configuration

- [ ] SecurityHeaders middleware is enabled ✅
- [ ] SecurityEventLogger middleware is enabled ✅
- [ ] Authorization policies registered ✅
- [ ] Apply rate limiting to routes (see `ROUTES_RATE_LIMITING.md`)
- [ ] Review CSRF exceptions (minimize to zero if possible)
- [ ] Configure HTTPS/SSL certificate
- [ ] Test security headers: https://securityheaders.com/
- [ ] Test SSL: https://www.ssllabs.com/ssltest/

### Phase 5: Password Migration

- [ ] Review `PASSWORD_MIGRATION_GUIDE.md`
- [ ] Choose migration strategy
- [ ] Prepare user communication emails
- [ ] Run password migration (high priority!)
- [ ] Monitor migration completion
- [ ] Force 2FA for admin accounts

### Phase 6: Testing

- [ ] Test login with rate limiting
- [ ] Test file uploads with validation
- [ ] Test payment processing
- [ ] Test admin authorization
- [ ] Load testing
- [ ] Security scanning
- [ ] Penetration testing (if possible)

### Phase 7: Monitoring & Logging

- [ ] Configure log rotation
- [ ] Set up error monitoring (Sentry, Bugsnag, etc.)
- [ ] Configure security event alerts
- [ ] Set up uptime monitoring
- [ ] Configure database backups
- [ ] Test backup restoration

---

## 📝 Step-by-Step Deployment

### Step 1: Prepare Server Environment

```bash
# Update server
sudo apt update && sudo apt upgrade -y

# Install required packages
sudo apt install -y nginx mysql-server php8.1-fpm php8.1-mysql \
    php8.1-mbstring php8.1-xml php8.1-curl php8.1-zip \
    php8.1-gd php8.1-bcmath redis-server

# Install Composer
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer

# Install Node.js and NPM (if needed)
curl -fsSL https://deb.nodesource.com/setup_18.x | sudo -E bash -
sudo apt install -y nodejs
```

### Step 2: Clone and Setup Application

```bash
# Navigate to web directory
cd /var/www

# Clone repository
git clone https://github.com/Digiwaxx/digiwaxx-app.git
cd digiwaxx-app

# Checkout security branch
git checkout claude/music-distribution-setup-01WgR45pdEMugea6qdeUGQyR

# Install dependencies
composer install --no-dev --optimize-autoloader

# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# Set permissions
sudo chown -R www-data:www-data /var/www/digiwaxx-app
sudo chmod -R 755 /var/www/digiwaxx-app
sudo chmod -R 775 /var/www/digiwaxx-app/storage
sudo chmod -R 775 /var/www/digiwaxx-app/bootstrap/cache
```

### Step 3: Configure Environment

Edit `.env` file:

```bash
nano .env
```

**Critical Settings:**

```env
APP_NAME=Digiwaxx
APP_ENV=production
APP_KEY=base64:YOUR_GENERATED_KEY
APP_DEBUG=false
APP_URL=https://yourdomain.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=digiwaxx_prod
DB_USERNAME=digiwaxx_user
DB_PASSWORD=STRONG_PASSWORD_HERE

# CRITICAL: Use NEW Stripe keys (rotate exposed keys!)
STRIPE_SECRET=sk_live_YOUR_NEW_LIVE_SECRET_KEY
STRIPE_PUBLISHABLE=pk_live_YOUR_NEW_LIVE_PUBLISHABLE_KEY
STRIPE_CURRENCY=usd

# pCloud Configuration
PCLOUD_ACCESS_TOKEN=your_production_token
PCLOUD_LOCATION_ID=1
PCLOUD_AUDIO_PATH=your_production_audio_folder

# Mail Configuration
MAIL_MAILER=smtp
MAIL_HOST=smtp.your-provider.com
MAIL_PORT=587
MAIL_USERNAME=your_email@domain.com
MAIL_PASSWORD=your_mail_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@digiwaxx.com
MAIL_FROM_NAME="${APP_NAME}"

# Session Security
SESSION_SECURE_COOKIE=true
SESSION_HTTP_ONLY=true
SESSION_SAME_SITE=lax

# Security Settings
SECURITY_MONITORING_ENABLED=true
LOG_FAILED_LOGIN_ATTEMPTS=true
LOG_FILE_UPLOADS=true
LOG_PAYMENT_TRANSACTIONS=true
```

### Step 4: Database Setup

```bash
# Create database
mysql -u root -p
```

```sql
CREATE DATABASE digiwaxx_prod CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'digiwaxx_user'@'localhost' IDENTIFIED BY 'STRONG_PASSWORD_HERE';
GRANT ALL PRIVILEGES ON digiwaxx_prod.* TO 'digiwaxx_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

```bash
# Import existing database
mysql -u digiwaxx_user -p digiwaxx_prod < your_database_backup.sql

# Run database indexes
mysql -u digiwaxx_user -p digiwaxx_prod < DATABASE_INDEXES.sql

# Verify indexes
mysql -u digiwaxx_user -p digiwaxx_prod -e "SHOW INDEX FROM clients;"
```

### Step 5: Configure Nginx

```bash
sudo nano /etc/nginx/sites-available/digiwaxx
```

**Nginx Configuration:**

```nginx
server {
    listen 80;
    listen [::]:80;
    server_name yourdomain.com www.yourdomain.com;

    # Redirect to HTTPS
    return 301 https://$server_name$request_uri;
}

server {
    listen 443 ssl http2;
    listen [::]:443 ssl http2;

    server_name yourdomain.com www.yourdomain.com;
    root /var/www/digiwaxx-app/public;

    index index.php index.html index.htm;

    # SSL Configuration
    ssl_certificate /etc/ssl/certs/your_cert.crt;
    ssl_certificate_key /etc/ssl/private/your_key.key;
    ssl_protocols TLSv1.2 TLSv1.3;
    ssl_ciphers HIGH:!aNULL:!MD5;
    ssl_prefer_server_ciphers on;

    # Security Headers (additional to Laravel middleware)
    add_header X-Frame-Options "SAMEORIGIN" always;
    add_header X-Content-Type-Options "nosniff" always;
    add_header X-XSS-Protection "1; mode=block" always;
    add_header Referrer-Policy "strict-origin-when-cross-origin" always;

    # File upload size
    client_max_body_size 100M;

    # Logging
    access_log /var/log/nginx/digiwaxx-access.log;
    error_log /var/log/nginx/digiwaxx-error.log;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_hide_header X-Powered-By;
    }

    # Block access to sensitive files
    location ~ /\.(?!well-known).* {
        deny all;
    }

    location ~ /\.env {
        deny all;
    }

    location ~ /\.git {
        deny all;
    }
}
```

```bash
# Enable site
sudo ln -s /etc/nginx/sites-available/digiwaxx /etc/nginx/sites-enabled/

# Test configuration
sudo nginx -t

# Restart Nginx
sudo systemctl restart nginx
```

### Step 6: SSL Certificate (Let's Encrypt)

```bash
# Install Certbot
sudo apt install certbot python3-certbot-nginx

# Obtain certificate
sudo certbot --nginx -d yourdomain.com -d www.yourdomain.com

# Test auto-renewal
sudo certbot renew --dry-run
```

### Step 7: Optimize Laravel

```bash
# Cache configuration
php artisan config:cache

# Cache routes
php artisan route:cache

# Cache views
php artisan view:cache

# Optimize autoloader
composer dump-autoload --optimize
```

### Step 8: Set Up Supervisor (Queue Workers)

If using queues:

```bash
sudo apt install supervisor

sudo nano /etc/supervisor/conf.d/digiwaxx-worker.conf
```

```ini
[program:digiwaxx-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/digiwaxx-app/artisan queue:work --sleep=3 --tries=3
autostart=true
autorestart=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=/var/www/digiwaxx-app/storage/logs/worker.log
```

```bash
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start digiwaxx-worker:*
```

### Step 9: Set Up Cron Jobs

```bash
sudo crontab -e -u www-data
```

Add:

```cron
# Laravel Scheduler
* * * * * cd /var/www/digiwaxx-app && php artisan schedule:run >> /dev/null 2>&1

# Database Backup (daily at 2 AM)
0 2 * * * /usr/bin/mysqldump -u digiwaxx_user -pPASSWORD digiwaxx_prod > /var/backups/digiwaxx_$(date +\%Y\%m\%d).sql

# Database Optimization (weekly, Sundays at 3 AM)
0 3 * * 0 mysql -u digiwaxx_user -pPASSWORD digiwaxx_prod -e "OPTIMIZE TABLE clients, members, tracks, tracks_mp3s;"

# Log Rotation (daily at 1 AM)
0 1 * * * find /var/www/digiwaxx-app/storage/logs/*.log -mtime +30 -delete
```

### Step 10: Apply Rate Limiting

Copy route examples from `ROUTES_RATE_LIMITING.md` to your `routes/web.php`:

```bash
# In your Laravel project root
nano routes/web.php
```

Apply the rate limiting examples from the documentation.

### Step 11: Password Migration

**CRITICAL: Must be done ASAP**

Follow `PASSWORD_MIGRATION_GUIDE.md`:

```bash
# Add database columns (from guide)
mysql -u digiwaxx_user -p digiwaxx_prod

# Run the ALTER TABLE commands from PASSWORD_MIGRATION_GUIDE.md

# Send notification emails to users
# Implement password reset requirement
```

### Step 12: Configure Firewall

```bash
# Install UFW
sudo apt install ufw

# Allow SSH
sudo ufw allow 22/tcp

# Allow HTTP/HTTPS
sudo ufw allow 80/tcp
sudo ufw allow 443/tcp

# Enable firewall
sudo ufw enable

# Check status
sudo ufw status
```

### Step 13: Set Up Monitoring

**Install Monitoring Tools:**

```bash
# Install Monit
sudo apt install monit

# Configure Monit
sudo nano /etc/monit/monitrc
```

**Monit Configuration:**

```
set daemon 60
set log /var/log/monit.log

check process nginx with pidfile /var/run/nginx.pid
    start program = "/usr/bin/systemctl start nginx"
    stop program = "/usr/bin/systemctl stop nginx"

check process mysql with pidfile /var/run/mysqld/mysqld.pid
    start program = "/usr/bin/systemctl start mysql"
    stop program = "/usr/bin/systemctl stop mysql"

check process php-fpm with pidfile /var/run/php/php8.1-fpm.pid
    start program = "/usr/bin/systemctl start php8.1-fpm"
    stop program = "/usr/bin/systemctl stop php8.1-fpm"

check filesystem storage with path /var/www/digiwaxx-app/storage
    if space usage > 80% then alert
```

```bash
sudo systemctl enable monit
sudo systemctl start monit
```

---

## 🔍 Post-Deployment Testing

### 1. Security Headers Test

```bash
curl -I https://yourdomain.com
```

Verify headers:
- X-Frame-Options
- X-Content-Type-Options
- Strict-Transport-Security
- Content-Security-Policy

### 2. SSL Test

Visit: https://www.ssllabs.com/ssltest/analyze.html?d=yourdomain.com

Target: A+ rating

### 3. Rate Limiting Test

```bash
# Test login rate limiting (should block after 5 attempts)
for i in {1..6}; do
  curl -X POST https://yourdomain.com/login \
    -d "email=test@test.com&password=wrong&membertype=client"
  echo "\nAttempt $i"
done
```

### 4. File Upload Test

- Upload valid image → Should succeed
- Upload PHP file → Should be blocked
- Upload 10MB file → Should be blocked (if limit is 5MB)

### 5. Payment Test

Use Stripe test mode to verify:
- Amount validation works
- Tampering is prevented
- Transactions are logged

### 6. Performance Test

```bash
# Install Apache Bench
sudo apt install apache2-utils

# Run load test
ab -n 1000 -c 10 https://yourdomain.com/
```

---

## 📊 Monitoring & Maintenance

### Daily Tasks

- [ ] Check error logs: `tail -f /var/www/digiwaxx-app/storage/logs/laravel.log`
- [ ] Check Nginx logs: `tail -f /var/log/nginx/digiwaxx-error.log`
- [ ] Review security events
- [ ] Monitor failed login attempts

### Weekly Tasks

- [ ] Review database performance
- [ ] Check disk space
- [ ] Review backup integrity
- [ ] Update packages: `composer update` (test first!)
- [ ] Review security logs

### Monthly Tasks

- [ ] Run security scan
- [ ] Review and rotate logs
- [ ] Database optimization: `OPTIMIZE TABLE`
- [ ] Review and update SSL certificate
- [ ] Security audit

---

## 🚨 Incident Response

### If Attacked

1. **Identify the attack vector**
   - Check logs: `/var/www/digiwaxx-app/storage/logs/`
   - Check Nginx logs: `/var/log/nginx/`

2. **Block malicious IPs**
   ```bash
   sudo ufw deny from ATTACKER_IP
   ```

3. **Enable maintenance mode**
   ```bash
   php artisan down
   ```

4. **Investigate**
   - Review security event logs
   - Check for unauthorized access
   - Check for data modifications

5. **Remediate**
   - Patch vulnerabilities
   - Restore from backup if needed
   - Force password resets if compromised

6. **Resume operations**
   ```bash
   php artisan up
   ```

---

## 📞 Support Contacts

- **Laravel Support:** https://laracasts.com/discuss
- **Stripe Support:** https://support.stripe.com/
- **pCloud Support:** https://www.pcloud.com/help/
- **Security Issues:** Report via GitHub Issues

---

## ✅ Deployment Complete Checklist

Once all steps are complete:

- [ ] Application accessible via HTTPS
- [ ] SSL certificate valid (A+ rating)
- [ ] All security headers present
- [ ] Rate limiting functional
- [ ] File uploads validated
- [ ] Payments processing securely
- [ ] Logs being written
- [ ] Backups configured
- [ ] Monitoring active
- [ ] Password migration planned
- [ ] Admin 2FA enabled
- [ ] Documentation updated
- [ ] Team trained on security procedures

---

## 🎉 Success Criteria

Your deployment is successful when:

✅ Security score is 8/10 or higher
✅ No critical vulnerabilities in security scan
✅ SSL test shows A+ rating
✅ All automated backups working
✅ Monitoring alerts configured
✅ Password migration scheduled
✅ Team trained on incident response
✅ Zero downtime during deployment

---

**Congratulations! Your Digiwaxx application is now production-ready with enterprise-grade security! 🚀🔒**

---

Last Updated: 2025-11-20
Version: 1.0
