# Nginx Configuration untuk Laravel + Cloudflare Tunnel

## 📋 Daftar Setup

### Step 1: Instalasi Dependencies di Server Linux (Ubuntu/Debian)

```bash
# Update system
sudo apt update && sudo apt upgrade -y

# Install Nginx
sudo apt install nginx -y

# Install PHP dan ekstensi yang diperlukan
sudo apt install php8.2-fpm php8.2-cli php8.2-common php8.2-mysql php8.2-mbstring php8.2-xml php8.2-curl php8.2-bcmath php8.2-gd php8.2-json php8.2-zip php8.2-opcache -y

# Install Composer
curl -sS https://getcomposer.org/installer -o composer-setup.php
php composer-setup.php --install-dir=/usr/local/bin --filename=composer
rm composer-setup.php

# Install Cloudflare Tunnel (cloudflared)
curl -L --output cloudflared.deb https://github.com/cloudflare/cloudflared/releases/latest/download/cloudflared-linux-amd64.deb
sudo dpkg -i cloudflared.deb
rm cloudflared.deb
```

### Step 2: Clone dan Setup Laravel Application

```bash
# Buat direktori
sudo mkdir -p /var/www/wisata-web
cd /var/www/wisata-web

# Clone repository (atau upload via SCP)
sudo git clone <your-repo-url> .
# atau jika sudah ada, tinggal upload files

# Set permissions
sudo chown -R www-data:www-data /var/www/wisata-web
sudo chmod -R 755 /var/www/wisata-web
sudo chmod -R 775 /var/www/wisata-web/storage
sudo chmod -R 775 /var/www/wisata-web/bootstrap/cache

# Install dependencies
sudo -u www-data composer install --no-dev --optimize-autoloader

# Copy .env dan setup
sudo cp .env.example .env
sudo -u www-data php artisan key:generate
sudo -u www-data php artisan storage:link

# Run migrations
sudo -u www-data php artisan migrate --force
sudo -u www-data php artisan db:seed --force
```

### Step 3: Configure Nginx

```bash
# Copy nginx.conf ke Nginx
sudo cp /var/www/wisata-web/nginx.conf /etc/nginx/sites-available/wisata-web

# Enable site
sudo ln -s /etc/nginx/sites-available/wisata-web /etc/nginx/sites-enabled/wisata-web

# Disable default site (optional)
sudo rm -f /etc/nginx/sites-enabled/default

# Test konfigurasi
sudo nginx -t

# Restart Nginx
sudo systemctl restart nginx
sudo systemctl enable nginx
```

### Step 4: Configure PHP-FPM

```bash
# Edit PHP-FPM config
sudo nano /etc/php/8.2/fpm/pool.d/www.conf

# Pastikan ini ada:
# listen = /run/php/php-fpm.sock
# listen.owner = www-data
# listen.group = www-data
# listen.mode = 0666

# Restart PHP-FPM
sudo systemctl restart php8.2-fpm
sudo systemctl enable php8.2-fpm
```

### Step 5: Setup Cloudflare Tunnel

```bash
# Authenticate dengan Cloudflare
cloudflared tunnel login

# Buat tunnel baru
cloudflared tunnel create wisata-web

# Dapatkan credentials file path
# Biasanya di: ~/.cloudflared/<tunnel-id>.json

# Buat config file untuk tunnel
sudo nano ~/.cloudflared/config.yml
```

**Isi file config.yml:**
```yaml
tunnel: wisata-web
credentials-file: /root/.cloudflared/<tunnel-id>.json
log-level: info

ingress:
  - hostname: wisata.yourdomain.com
    service: http://localhost:80
  - hostname: api.yourdomain.com
    service: http://localhost:80
  - hostname: admin.yourdomain.com
    service: http://localhost:80
  - service: http_status:404
```

```bash
# Setup tunnel sebagai service
sudo cloudflared service install

# Restart service
sudo systemctl restart cloudflared
sudo systemctl enable cloudflared

# Cek status tunnel
sudo systemctl status cloudflared
```

### Step 6: Setup Certificate & DNS di Cloudflare

1. Masuk ke Cloudflare Dashboard
2. Tambahkan domain Anda (jika belum ada)
3. Arahkan nameservers ke Cloudflare
4. Di DNS settings, arahkan subdomain ke tunnel:
   - Type: CNAME
   - Name: wisata
   - Target: `<tunnel-id>.cfargotunnel.com`
5. Enable "Proxy" (orange cloud)

### Step 7: Optimize Laravel untuk Production

```bash
cd /var/www/wisata-web

# Cache configuration
sudo -u www-data php artisan config:cache

# Cache routes
sudo -u www-data php artisan route:cache

# Cache views
sudo -u www-data php artisan view:cache

# Optimize autoloader
sudo -u www-data composer install --no-dev --optimize-autoloader

# Clear all caches
sudo -u www-data php artisan cache:clear
sudo -u www-data php artisan optimize
```

## 🔒 Security Recommendations

### 1. Setup Firewall
```bash
sudo ufw default deny incoming
sudo ufw default allow outgoing
sudo ufw allow 22/tcp
sudo ufw allow 80/tcp
sudo ufw allow 443/tcp
sudo ufw enable
```

### 2. SSL Certificate (Optional - jika tidak pakai Cloudflare tunnel)
```bash
sudo apt install certbot python3-certbot-nginx -y
sudo certbot certonly --nginx -d wisata.yourdomain.com
```

### 3. Monitor Logs
```bash
# Real-time logs
sudo tail -f /var/log/nginx/access.log
sudo tail -f /var/log/nginx/error.log
sudo tail -f /var/log/php8.2-fpm.log

# Check for errors
sudo grep error /var/log/nginx/error.log | tail -20
```

## 📊 Performance Monitoring

### Memory & CPU Usage
```bash
# Monitor dalam real-time
htop

# Check PHP-FPM processes
ps aux | grep php-fpm

# Check Nginx processes
ps aux | grep nginx
```

### Database Optimization (MySQL)
```bash
# Add to /etc/mysql/mysql.conf.d/mysqld.cnf
[mysqld]
max_connections = 500
query_cache_size = 64M
query_cache_type = 1
innodb_buffer_pool_size = 1G
innodb_log_file_size = 256M
```

## 🐛 Troubleshooting

### Nginx not running
```bash
sudo nginx -t  # Test configuration
sudo systemctl status nginx  # Check status
sudo systemctl restart nginx  # Restart
```

### Permission issues
```bash
sudo chown -R www-data:www-data /var/www/wisata-web
sudo chmod -R 755 /var/www/wisata-web
sudo chmod -R 775 /var/www/wisata-web/storage
sudo chmod -R 775 /var/www/wisata-web/bootstrap/cache
```

### Cloudflare Tunnel not connecting
```bash
# Restart tunnel
sudo systemctl restart cloudflared

# Check tunnel status
cloudflared tunnel list

# Check logs
sudo journalctl -u cloudflared -f
```

### PHP errors
```bash
# Enable error logging in .env
APP_DEBUG=false  # Production
LARAVEL_LOG=single

# Check Laravel logs
tail -f /var/www/wisata-web/storage/logs/laravel.log
```

## 📝 File Permissions Reference

| Path | Owner | Permissions | Reason |
|------|-------|-------------|--------|
| `/var/www/wisata-web` | www-data:www-data | 755 | Application root |
| `/var/www/wisata-web/storage` | www-data:www-data | 775 | Write access needed |
| `/var/www/wisata-web/bootstrap/cache` | www-data:www-data | 775 | Write access needed |
| `/var/www/wisata-web/.env` | www-data:www-data | 600 | Sensitive config |

## 🚀 Initial Deployment Checklist

- [ ] Server disetup dengan Nginx + PHP-FPM
- [ ] Laravel application sudah di-clone/upload
- [ ] Dependencies terinstall (composer install)
- [ ] Database sudah di-migrate
- [ ] File permissions sudah di-set dengan benar
- [ ] .env file sudah dikonfigurasi
- [ ] Cloudflare Tunnel sudah authenticated & running
- [ ] DNS di Cloudflare sudah pointing ke tunnel
- [ ] SSL/TLS mode di Cloudflare sudah di-set (Full atau Full Strict)
- [ ] Application caches sudah di-generate
- [ ] Logs bisa diakses untuk monitoring

## 📧 Contact & Support

Untuk masalah lebih lanjut:
1. Check Laravel logs: `/var/www/wisata-web/storage/logs/laravel.log`
2. Check Nginx logs: `/var/log/nginx/error.log`
3. Check system logs: `sudo journalctl -xe`
