# Quick Reference Guide - Nginx + Laravel + Cloudflare Tunnel

## 📚 File Descriptions

| File | Purpose |
|------|---------|
| `nginx.conf` | Main Nginx configuration dengan Cloudflare integration |
| `php-fpm-pool.conf` | PHP-FPM pool configuration untuk optimal performance |
| `cloudflared-config.yml` | Cloudflare Tunnel configuration |
| `deploy.sh` | Automated deployment script (jalankan dengan sudo) |
| `NGINX_SETUP_GUIDE.md` | Detailed step-by-step setup guide |

---

## ⚡ Quick Commands

### 1️⃣ Copy Files ke Server
```bash
scp -r nginx.conf root@server-ip:/tmp/
scp -r php-fpm-pool.conf root@server-ip:/tmp/
scp -r cloudflared-config.yml root@server-ip:/tmp/
scp -r deploy.sh root@server-ip:/tmp/

# SSH ke server
ssh root@server-ip
```

### 2️⃣ Run Deployment (AUTOMATED)
```bash
cd /tmp
bash deploy.sh
```

### 3️⃣ Manual Setup (jika tidak pakai script)

**Setup Nginx:**
```bash
sudo cp /tmp/nginx.conf /etc/nginx/sites-available/wisata-web
sudo sed -i 's|/var/www/wisata-web|/path/to/app|g' /etc/nginx/sites-available/wisata-web
sudo ln -s /etc/nginx/sites-available/wisata-web /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl restart nginx
```

**Setup PHP-FPM:**
```bash
sudo cp /tmp/php-fpm-pool.conf /etc/php/8.2/fpm/pool.d/www.conf
sudo systemctl restart php8.2-fpm
```

**Setup Cloudflare Tunnel:**
```bash
cloudflared tunnel login
cloudflared tunnel create wisata-web
sudo nano ~/.cloudflared/config.yml  # Copy isi dari cloudflared-config.yml
sudo cloudflared service install
sudo systemctl start cloudflared
```

---

## 🔍 Monitoring & Troubleshooting

### Check Status
```bash
# Nginx
sudo systemctl status nginx
sudo nginx -t  # Test config

# PHP-FPM
sudo systemctl status php8.2-fpm

# Cloudflare Tunnel
sudo systemctl status cloudflared
cloudflared tunnel list
```

### View Logs
```bash
# Nginx Access Log
sudo tail -100f /var/log/nginx/access.log

# Nginx Error Log
sudo tail -100f /var/log/nginx/error.log

# PHP-FPM Error Log
sudo tail -100f /var/log/php-fpm/error.log

# Laravel Log
tail -100f /var/www/wisata-web/storage/logs/laravel.log

# Cloudflare Tunnel Log
sudo journalctl -u cloudflared -n 50 -f

# System Logs
sudo journalctl -xe
```

### Restart Services
```bash
# Restart all
sudo systemctl restart nginx php8.2-fpm cloudflared

# Restart individual
sudo systemctl restart nginx
sudo systemctl restart php8.2-fpm
sudo systemctl restart cloudflared
```

### Fix Permissions
```bash
sudo chown -R www-data:www-data /var/www/wisata-web
sudo chmod -R 755 /var/www/wisata-web
sudo chmod -R 775 /var/www/wisata-web/storage
sudo chmod -R 775 /var/www/wisata-web/bootstrap/cache
```

---

## 🚀 Common Tasks

### Update Application
```bash
cd /var/www/wisata-web
git pull origin main
sudo -u www-data composer install --no-dev --optimize-autoloader
sudo -u www-data php artisan migrate --force
sudo -u www-data php artisan cache:clear
sudo -u www-data php artisan config:cache
```

### View Active Connections
```bash
ps aux | grep php-fpm | grep -v grep
ps aux | grep nginx | grep -v grep
```

### Monitor System Resources
```bash
# Real-time monitoring
htop

# Memory usage
free -h

# Disk usage
df -h

# CPU usage
top -n 1
```

### Clear All Caches
```bash
cd /var/www/wisata-web
sudo -u www-data php artisan cache:clear
sudo -u www-data php artisan config:clear
sudo -u www-data php artisan view:clear
sudo -u www-data php artisan route:clear
```

### Regenerate Caches (Production)
```bash
cd /var/www/wisata-web
sudo -u www-data php artisan config:cache
sudo -u www-data php artisan route:cache
sudo -u www-data php artisan view:cache
sudo -u www-data php artisan optimize
```

---

## 🔒 Security Checklist

- [ ] Firewall enabled (UFW)
- [ ] SSH key auth (no password login)
- [ ] Fail2Ban configured
- [ ] SSL/TLS enabled di Cloudflare
- [ ] WAF rules enabled di Cloudflare
- [ ] .env file permissions (600)
- [ ] Storage directory tidak public
- [ ] APP_DEBUG = false
- [ ] HTTPS redirection enabled
- [ ] Rate limiting configured (sudah di nginx.conf)

### Additional Security (Optional)
```bash
# Install Fail2Ban
sudo apt install fail2ban -y
sudo systemctl enable fail2ban

# Configure SSH
sudo nano /etc/ssh/sshd_config
# Change: PermitRootLogin no
# Change: PasswordAuthentication no

# Restart SSH
sudo systemctl restart ssh
```

---

## 📊 Performance Tuning

### Nginx Performance
```bash
# Di nginx.conf, adjust:
worker_connections 2048;  # dari 768
keepalive_timeout 45;      # dari 65
```

### PHP-FPM Performance
```bash
# Di /etc/php/8.2/fpm/pool.d/www.conf
pm.max_children = 100      # Tingkatkan sesuai RAM
pm.start_servers = 20      # Lebih banyak proses awal
pm.min_spare_servers = 10
pm.max_spare_servers = 40
```

### MySQL Optimization
```bash
sudo nano /etc/mysql/mysql.conf.d/mysqld.cnf

# Add/modify:
max_connections = 500
query_cache_size = 128M
innodb_buffer_pool_size = 2G
```

---

## 🔗 Cloudflare Tunnel Setup

### 1. Authenticate
```bash
cloudflared tunnel login
# Akan membuka browser untuk autentikasi Cloudflare
```

### 2. Create Tunnel
```bash
cloudflared tunnel create wisata-web
# Akan generate tunnel ID dan credentials file
```

### 3. Configure
Edit `~/.cloudflared/config.yml`:
```yaml
tunnel: wisata-web
credentials-file: ~/.cloudflared/<tunnel-id>.json

ingress:
  - hostname: wisata.yourdomain.com
    service: http://localhost:80
  - service: http_status:404
```

### 4. Setup DNS (Cloudflare Dashboard)
- Type: CNAME
- Name: wisata
- Content: <tunnel-id>.cfargotunnel.com
- Proxy: ON (orange cloud)

### 5. Start Service
```bash
sudo cloudflared service install
sudo systemctl start cloudflared
sudo systemctl enable cloudflared
```

### 6. Verify Connection
```bash
cloudflared tunnel list
cloudflared tunnel info wisata-web
```

---

## 🐛 Common Issues & Solutions

| Issue | Solution |
|-------|----------|
| 502 Bad Gateway | Check PHP-FPM: `systemctl status php8.2-fpm` |
| Permission denied | Fix permissions: `sudo chown -R www-data:www-data /var/www/wisata-web` |
| Nginx won't start | Test: `sudo nginx -t` |
| Tunnel not connecting | Restart: `sudo systemctl restart cloudflared` |
| Database connection error | Check .env: `APP_DB_*` variables |
| Storage not writable | Check perms: `chmod 775 storage bootstrap/cache` |
| Slow requests | Check logs, increase PHP workers, optimize query |

---

## 📞 Help & Logs

Jika ada masalah, check lognya:

```bash
# Check all status
sudo systemctl status nginx php8.2-fpm cloudflared

# Check errors
sudo tail -50f /var/log/nginx/error.log
sudo tail -50f /var/log/php-fpm/error.log
sudo journalctl -u cloudflared -n 50

# Check Laravel
tail -50f /var/www/wisata-web/storage/logs/laravel.log
```

---

## 📝 Configuration Customization

### Update Domain
```bash
# Di nginx.conf - cari dan ganti:
server_name wisata-domain.com;

# Di cloudflared-config.yml:
hostname: wisata-domain.com
```

### Adjust PHP Limits
```bash
# Di nginx.conf:
fastcgi_param PHP_VALUE "upload_max_filesize=200M \n post_max_size=200M";

# Di php-fpm-pool.conf:
php_admin_value[upload_max_filesize] = 200M
php_admin_value[post_max_size] = 200M
```

### Custom Error Pages
```bash
# Di nginx.conf, tambahkan:
error_page 404 /404.html;
error_page 500 502 503 504 /50x.html;
location = /50x.html {
    root /var/www/wisata-web/public;
}
```

---

## 🎯 Final Checklist

- [ ] Server setup complete
- [ ] Nginx running dan config valid
- [ ] PHP-FPM running dengan optimal config
- [ ] Laravel application deployed
- [ ] Database migrations complete
- [ ] Cloudflare Tunnel authenticated & running
- [ ] DNS pointing to tunnel
- [ ] SSL/TLS mode set di Cloudflare
- [ ] Testing accessing via tunnel URL
- [ ] Logs monitored & errors resolved
- [ ] Firewall configured
- [ ] Backups scheduled
- [ ] Monitoring tools setup

---

Untuk dokumentasi lengkap, lihat: **NGINX_SETUP_GUIDE.md**
