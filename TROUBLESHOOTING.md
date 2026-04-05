# Troubleshooting & FAQ Guide

## ❌ Issues & Solutions

### 1. Nginx Issues

#### "nginx: [error] open() "/run/nginx.pid" failed"
```bash
# Solution:
sudo mkdir -p /run/nginx
sudo touch /run/nginx.pid
sudo chown www-data:www-data /run/nginx.pid
sudo systemctl restart nginx
```

#### "502 Bad Gateway" Error
```bash
# Check PHP-FPM adalah running
sudo systemctl status php8.2-fpm

# Check PHP-FPM socket exists
ls -la /run/php/php-fpm.sock

# Restart PHP-FPM
sudo systemctl restart php8.2-fpm

# Check error log
sudo tail -50f /var/log/php-fpm/error.log
```

#### "Permission denied while reading /var/www/wisata-web"
```bash
# Fix permissions
sudo chown -R www-data:www-data /var/www/wisata-web
sudo chmod -R 755 /var/www/wisata-web
sudo chmod -R 775 /var/www/wisata-web/storage
sudo chmod -R 775 /var/www/wisata-web/bootstrap/cache
```

#### "Unable to connect upstream: socket: connect call failed"
```bash
# Ensure PHP-FPM socket memiliki permission yang benar
sudo ls -la /run/php/php-fpm.sock

# Jika tidak ada, cek PHP-FPM config
sudo nano /etc/php/8.2/fpm/pool.d/www.conf
# Pastikan: listen = /run/php/php-fpm.sock

# Restart PHP-FPM
sudo systemctl restart php8.2-fpm

# Check PHP-FPM error log
sudo tail -50f /var/log/php-fpm/error.log
```

#### Nginx Configuration Test Failed
```bash
# Test dengan verbose output
sudo nginx -T

# Cek syntax khususnya di nginx.conf
sudo nano /etc/nginx/sites-available/wisata-web

# Check jika ada typo dalam path atau variable
```

---

### 2. PHP-FPM Issues

#### PHP-FPM tidak bisa reach database
```bash
# Check database connection
mysql -h 127.0.0.1 -u wisata_user -p

# Cek .env database config
cat /var/www/wisata-web/.env | grep DB_

# Test connection via Laravel
cd /var/www/wisata-web
sudo -u www-data php artisan tinker
# Di prompt, jalankan: DB::connection()->getPdo();
```

#### "PHP Fatal error: Allowed memory exhausted"
```bash
# Increase memory limit di php-fpm-pool.conf
sudo nano /etc/php/8.2/fpm/pool.d/www.conf

# Cari dan edit:
php_admin_value[memory_limit] = 512M  # Tingkatkan dari 256M

# Atau di .env:
# LARAVEL_LOG_LEVEL=info

# Restart PHP-FPM
sudo systemctl restart php8.2-fpm
```

#### "Warning: Session data file is not created"
```bash
# Ensure session directory writable
sudo mkdir -p /var/lib/php/sessions
sudo chown www-data:www-data /var/lib/php/sessions
sudo chmod 775 /var/lib/php/sessions

# Edit PHP config
sudo nano /etc/php/8.2/fpm/php.ini
# session.save_path = "/var/lib/php/sessions"

# Restart PHP-FPM
sudo systemctl restart php8.2-fpm
```

#### OPcache Issues
```bash
# Clear OPcache
sudo -u www-data php -r 'opcache_reset();'

# Atau via Laravel
cd /var/www/wisata-web
sudo -u www-data php artisan cache:clear

# Verify OPcache status
php -m | grep opcache
```

---

### 3. Cloudflare Tunnel Issues

#### "Error 2000: DNS Resolution Error"
```bash
# Ensure DNS pointing to tunnel
# Check di Cloudflare Dashboard > DNS

# Verify tunnel config
cat ~/.cloudflared/config.yml

# Test connection
cloudflared tunnel info wisata-web

# Restart tunnel
sudo systemctl restart cloudflared
```

#### "FAILED req id: 0 CF-RAY: ---"
```bash
# Tunnel tidak bisa reach origin server

# Check jika Nginx running
sudo systemctl status nginx

# Check jika port 80 accessible dari tunnel server
sudo netstat -tlnp | grep :80

# Harus ada: tcp 0 0 127.0.0.1:80 LISTEN

# Restart tunnel
sudo systemctl restart cloudflared
```

#### "Tunnel authentication failed"
```bash
# Re-authenticate
cloudflared tunnel login

# Check credentials file
ls -la ~/.cloudflared/

# Di config.yml, update credentials path:
credentials-file: ~/.cloudflared/<correct-tunnel-id>.json

# Restart
sudo systemctl restart cloudflared
```

#### "Connection timeout"
```bash
# Check firewall
sudo ufw status

# Allow Cloudflare outbound
sudo ufw allow out 443/tcp

# Verify tunnel running
sudo systemctl status cloudflared

# Check logs
sudo journalctl -u cloudflared -n 50 -f
```

#### Tunnel shows "Healthy" tapi tidak bisa access
```bash
# Wait 5-10 detik untuk DNS propagation

# Clear browser cache
# Ctrl+Shift+Delete (Chrome) atau Cmd+Shift+Delete (Mac)

# Atau test via curl
curl -I https://wisata.yourdomain.com

# Check Cloudflare WAF rules
# Mungkin blocking traffic
```

---

### 4. Database Issues

#### "Too many connections"
```bash
# Reduce PHP-FPM max children
sudo nano /etc/php/8.2/fpm/pool.d/www.conf

# pm.max_children = 20  # Kurangi dari 50
# pm.start_servers = 5
# pm.max_spare_servers = 10

# Restart
sudo systemctl restart php8.2-fpm
```

#### "MySQL has gone away"
```bash
# Increase MySQL timeout
sudo nano /etc/mysql/mysql.conf.d/mysqld.cnf

[mysqld]
wait_timeout = 28800
max_allowed_packet = 256M
interactive_timeout = 28800

# Restart MySQL
sudo systemctl restart mysql

# Cek connection pooling di Laravel
# config/database.php harus punya sticky = true
```

#### "Access denied for user 'wisata_user'@'localhost'"
```bash
# Check .env credentials
grep DB_ /var/www/wisata-web/.env

# Reset MySQL user
mysql -u root -p

# Di MySQL:
ALTER USER 'wisata_user'@'localhost' IDENTIFIED BY 'new_password';
FLUSH PRIVILEGES;

# Update .env
DB_PASSWORD=new_password
```

---

### 5. Storage & File Permission Issues

#### "The storage path is not writable"
```bash
# Fix storage permissions
cd /var/www/wisata-web
sudo chown -R www-data:www-data storage
sudo chmod -R 775 storage

# Also bootstrap cache
sudo chown -R www-data:www-data bootstrap/cache
sudo chmod -R 775 bootstrap/cache
```

#### "Unable to create cache file"
```bash
# Ensure cache directory exists dan writable
sudo mkdir -p /var/www/wisata-web/bootstrap/cache
sudo chown www-data:www-data /var/www/wisata-web/bootstrap/cache
sudo chmod 775 /var/www/wisata-web/bootstrap/cache

# Clear cache
cd /var/www/wisata-web
sudo -u www-data php artisan cache:clear
sudo -u www-data php artisan config:clear
```

#### "File upload fails"
```bash
# Check upload size limits di nginx.conf
grep -i client_max_body_size /etc/nginx/sites-available/wisata-web

# Jika tidak ada, tambahkan:
client_max_body_size 100M;

# Check PHP limits
grep -E "upload_max_filesize|post_max_size" /etc/php/8.2/fpm/pool.d/www.conf

# Ensure storage symlink exists
ls -la /var/www/wisata-web/public/storage

# Jika tidak ada, jalankan:
cd /var/www/wisata-web
sudo -u www-data php artisan storage:link
```

---

### 6. Application Issues

#### "Laravel Application Doesn't Start"
```bash
# Check Laravel logs
tail -100f /var/www/wisata-web/storage/logs/laravel.log

# Check permissions
cd /var/www/wisata-web
sudo chown -R www-data:www-data .
sudo find . -type f -exec chmod 644 {} \;
sudo find . -type d -exec chmod 755 {} \;

# Regenerate key
sudo -u www-data php artisan key:generate

# Clear caches
sudo -u www-data php artisan cache:clear
sudo -u www-data php artisan config:clear
```

#### "Artisan Commands Fail"
```bash
# Pastikan environment variables di-set
export APP_ENV=production
export APP_DEBUG=false

# Jalankan command sebagai www-data
sudo -u www-data php artisan migrate

# Check php.ini settings
sudo php -ini | grep memory_limit
```

#### "Class Not Found" Errors
```bash
# Regenerate autoloader
cd /var/www/wisata-web
sudo -u www-data composer dump-autoload

# Clear config cache
sudo -u www-data php artisan config:clear
```

---

## ❓ FAQ

### Q: Bagaimana cara check apakah semuanya running?
```bash
# All-in-one check
sudo systemctl status nginx php8.2-fpm cloudflared

# Detailed check
echo "=== NGINX ===" && sudo systemctl is-active nginx
echo "=== PHP-FPM ===" && sudo systemctl is-active php8.2-fpm
echo "=== CLOUDFLARE ===" && sudo systemctl is-active cloudflared

# Connection test
curl -I http://localhost
```

### Q: Bagaimana cara lihat real-time logs?
```bash
# Tail multiple logs sekaligus
tail -f /var/log/nginx/error.log /var/log/php-fpm/error.log /var/www/wisata-web/storage/logs/laravel.log

# Atau gunakan: less +F filename
```

### Q: Bagaimana cara optimize performance?
```bash
# Laravel caching
cd /var/www/wisata-web
sudo -u www-data php artisan config:cache
sudo -u www-data php artisan route:cache
sudo -u www-data php artisan view:cache

# Composer optimization
sudo -u www-data composer install --no-dev --optimize-autoloader

# Check slow queries di database
sudo mysql -u root -p
mysql> SET GLOBAL slow_query_log = 'ON';
mysql> SET GLOBAL long_query_time = 2;
```

### Q: Bagaimana backup database?
```bash
# Manual backup
mysqldump -u wisata_user -p wisata_db > backup_$(date +%Y%m%d).sql

# Automated backup (cron job)
# Edit crontab: sudo crontab -e
0 2 * * * mysqldump -u wisata_user -pPASSWORD wisata_db | gzip > /backups/db_$(date +\%Y\%m\%d).sql.gz
```

### Q: Bagaimana reset password user?
```bash
# Di MySQL
mysql -u root -p
mysql> UPDATE users SET password = bcrypt('new_password') WHERE id = 1;

# Atau via Laravel tinker
cd /var/www/wisata-web
sudo -u www-data php artisan tinker
>>> $user = User::first();
>>> $user->password = Hash::make('new_password');
>>> $user->save();
```

### Q: Bagaimana monitoring resource usage?
```bash
# Real-time
htop

# Memory
free -h

# Disk
df -h partisi

# Process specific
ps aux --sort=-%cpu | head -10  # Top CPU
ps aux --sort=-%mem | head -10  # Top Memory
```

### Q: Bagaimana handle SSL certificate renewal?
```bash
# Jika tidak pakai Cloudflare (manual SSL)
sudo certbot renew --dry-run  # Test
sudo certbot renew            # Renew

# Cloudflare otomatis handle SSL
# Tidak perlu manual renewal
```

### Q: Bagaimana handle high traffic?
```bash
# Increase PHP-FPM workers
pm.max_children = 100
pm.start_servers = 30

# Enable caching
CACHE_DRIVER=redis

# Enable query caching (MySQL)
query_cache_size = 256M
query_cache_type = 1

# Monitor dengan
watch -n 1 'ps aux | grep -E "php-fpm|nginx" | wc -l'
```

### Q: Bagaimana debug 502 Bad Gateway?
```bash
# Step 1: Check PHP-FPM
sudo systemctl status php8.2-fpm

# Step 2: Check socket
ls -la /run/php/php-fpm.sock

# Step 3: Check error logs
sudo tail -50 /var/log/nginx/error.log
sudo tail -50 /var/log/php-fpm/error.log

# Step 4: Restart both services
sudo systemctl restart php8.2-fpm nginx

# Step 5: Test
curl -I http://localhost
```

---

## 📋 Maintenance Schedule

| Task | Frequency | Command |
|------|-----------|---------|
| Clear logs | Weekly | `sudo journalctl --vacuum=30d` |
| Update system | Monthly | `sudo apt update && apt upgrade -y` |
| Database backup | Daily | `mysqldump ...` |
| Cache clear | As needed | `php artisan cache:clear` |
| Config cache | After deploy | `php artisan config:cache` |
| Monitor logs | Daily | `tail -f storage/logs/laravel.log` |
| Check disk space | Weekly | `df -h` |
| Security updates | ASAP | `apt upgrade` |

---

## 🔐 Security Hardening

```bash
# 1. Disable root login
sudo passwd -l root

# 2. Configure SSH
sudo nano /etc/ssh/sshd_config
# PermitRootLogin no
# PasswordAuthentication no
# PubkeyAuthentication yes

# 3. Enable UFW
sudo ufw enable
sudo ufw default deny incoming
sudo ufw default allow outgoing
sudo ufw allow 22/tcp
sudo ufw allow 80/tcp
sudo ufw allow 443/tcp

# 4. Fail2Ban (prevent brute force)
sudo apt install fail2ban -y
sudo systemctl enable fail2ban

# 5. Disable unnecessary services
sudo systemctl disable apache2 (jika ada)

# 6. Update regularly
sudo apt update && sudo apt upgrade -y
```

---

Untuk bantuan lebih lanjut, cek:
- Laravel Documentation: https://laravel.com/docs
- Nginx Documentation: https://nginx.org/en/docs/
- Cloudflare Tunnel: https://developers.cloudflare.com/cloudflare-one/
