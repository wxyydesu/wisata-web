# 🚀 Nginx + Laravel + Cloudflare Tunnel - Setup Package

Welcome! Ini adalah complete setup package untuk deploy Laravel application Anda dengan Nginx + Cloudflare Tunnel.

---

## 📦 Apa Yang Ada Di Package Ini?

### Configuration Files
| File | Deskripsi |
|------|-----------|
| **nginx.conf** | Master Nginx configuration dengan Cloudflare integration, Laravel routing, caching, security headers |
| **php-fpm-pool.conf** | PHP-FPM pool configuration untuk optimal performance |
| **cloudflared-config.yml** | Cloudflare Tunnel configuration dengan hostname & routing |
| **cloudflared.service** | Systemd service file untuk Cloudflare Tunnel (opsional) |

### Guides & Documentation
| File | Deskripsi |
|------|-----------|
| **README.md** | File ini - overview dan quick start |
| **NGINX_SETUP_GUIDE.md** | Step-by-step detailed guide untuk setup penuh |
| **QUICK_REFERENCE.md** | Quick commands reference untuk daily tasks |
| **TROUBLESHOOTING.md** | Common issues & solutions + FAQ |

### Scripts
| File | Deskripsi |
|------|-----------|
| **deploy.sh** | Automated deployment script (recommended) |

### Configuration Examples
| File | Deskripsi |
|------|-----------|
| **.env.production** | Production environment configuration contoh |

---

## ⚡ Quick Start (5 Menit)

### 1️⃣ Prepare Server
```bash
# Lokasi server pilihan (Ubuntu/Debian):
# - DigitalOceanDroplet: $5-12/bulan
# - Linode: $5+/bulan
# - AWS EC2: Pay as you go
# - Hetzner: €3+/bulan

# Minimum requirements:
# - Ubuntu 20.04 LTS / Debian 11+
# - 1GB RAM (2GB recommended)
# - 20GB SSD
# - Shell access (SSH)
```

### 2️⃣ Upload Files ke Server
```bash
scp nginx.conf root@server-ip:~/
scp php-fpm-pool.conf root@server-ip:~/
scp cloudflared-config.yml root@server-ip:~/
scp deploy.sh root@server-ip:~/
scp .env.production root@server-ip:~/
```

### 3️⃣ Connect & Run Script
```bash
ssh root@server-ip

# Jalankan automated script
sudo bash ~/deploy.sh

# Follow prompts dan selesaikan manual steps
```

### 4️⃣ Configure Cloudflare
```bash
# Di server, after script selesai:
cloudflared tunnel login
cloudflared tunnel create wisata-web

# Copy credentials file path dan update config.yml:
sudo nano ~/.cloudflared/config.yml

# Start service:
sudo systemctl start cloudflared
sudo systemctl enable cloudflared
```

### 5️⃣ Setup DNS
```
Cloudflare Dashboard > DNS > Add Record
Type: CNAME
Name: wisata
Target: <tunnel-id>.cfargotunnel.com
Proxy: ON (orange cloud)
```

✅ Done! Application sekarang accessible via tunnel.

---

## 📋 Step-by-Step untuk Pemula

### Jika belum punya server:

**OPTION 1: DigitalOcean (Recommended)**
1. Buat akun di https://digitalocean.com
2. Create Droplet > Ubuntu 22.04 LTS > $7/bulan minimum
3. Copy IP address
4. Open terminal di laptop & connect: `ssh root@YOUR_IP`

**OPTION 2: Linode**
1. Buat akun di https://linode.com
2. Create Linode > Ubuntu 22.04 LTS > €3+/bulan
3. Copy IP address
4. Connect: `ssh root@YOUR_IP`

### Setelah punya server:

**Step 1: Prepare Files**
```bash
# Di laptop Anda:
# Download/copy 7 files dari project ini ke folder lokal
ls -la  # Pastikan 7 files ada:
# - nginx.conf
# - php-fpm-pool.conf
# - cloudflared-config.yml
# - cloudflared.service
# - deploy.sh
# - .env.production
# - Bonus: documentation files
```

**Step 2: Upload**
```bash
# Di terminal laptop:
cd /path/to/folder/with/files

# Upload semua ke server
scp -r ./* root@123.456.789.000:~/setup/
ssh root@123.456.789.000

# Di server:
cd ~/setup
ls -la  # Verify semua files ada
```

**Step 3: Run Auto Deploy**
```bash
# Di server:
sudo bash deploy.sh

# Script akan:
# - Update system
# - Install Nginx, PHP, Composer
# - Setup directories & permissions
# - Configure Nginx & PHP-FPM
# - Install Cloudflare Tunnel
# - Setup database
# - Optimize Laravel

# IMPORTANT: Saat di-prompt untuk upload files,
# Upload folder project Laravel Anda ke server
```

**Step 4: Setup Cloudflare**
```bash
# Setelah script selesai:
cloudflared tunnel login
# Akan buka browser - login dengan Cloudflare account

cloudflared tunnel create wisata-web
# Catat tunnel ID yang di-generate

# Copy config:
sudo nano ~/.cloudflared/config.yml
# Paste isi dari file cloudflared-config.yml
# Update hostname ke domain Anda

# Restart tunnel:
sudo systemctl restart cloudflared
```

**Step 5: DNS Setup**
```
1. Buka https://dash.cloudflare.com
2. Login dengan Cloudflare account
3. Select domain Anda
4. Go to DNS section
5. Add CNAME record:
   - Name: wisata (atau sub-domain pilihan)
   - Content: <tunnel-id>.cfargotunnel.com
   - Proxy: ON (harus orange cloud)
6. Tunggu 5 menit untuk propagation
7. Akses https://wisata.yourdomain.com ✅
```

---

## 🔧 Customization

### Change Domain
```bash
# Edit nginx.conf
sudo nano /etc/nginx/sites-available/wisata-web
# Ganti: server_name wisata-domain.com;

# Edit cloudflared-config.yml
sudo nano ~/.cloudflared/config.yml
# Ganti hostname: wisata.yourdomain.com

# Restart services
sudo systemctl restart nginx cloudflared
```

### Adjust Upload Size
```bash
# nginx.conf
client_max_body_size 200M;

# php-fpm-pool.conf
php_admin_value[upload_max_filesize] = 200M
php_admin_value[post_max_size] = 200M

# Restart
sudo systemctl restart nginx php8.2-fpm
```

### Custom PHP Settings
```bash
# Edit php-fpm-pool.conf
sudo nano /etc/php/8.2/fpm/pool.d/www.conf

# Ubah pm.max_children, memory_limit, etc
# Restart PHP-FPM
sudo systemctl restart php8.2-fpm
```

---

## 🔍 Verification Checklist

```bash
# Gunakan commands ini untuk verify setup:

# 1. Check services running
sudo systemctl status nginx php8.2-fpm cloudflared

# 2. Test Nginx listening
sudo netstat -tlnp | grep -E "nginx|php"

# 3. Check socket permissions
ls -la /run/php/php-fpm.sock

# 4. Check Laravel setup
cd /var/www/wisata-web
sudo -u www-data php artisan migrate
sudo -u www-data php artisan cache:clear

# 5. Test local access (dari server)
curl -I http://localhost

# 6. Check Cloudflare tunnel
cloudflared tunnel list

# 7. Test Cloudflare DNS
dig wisata.yourdomain.com +short

# 8. Browse ke domain (dari laptop)
https://wisata.yourdomain.com
```

✅ Semua berhasil jika tidak ada error messages.

---

## 📊 Understanding The Architecture

```
┌─────────────────────────────────────────────────┐
│         Browser / User (External)               │
└────────────────────┬────────────────────────────┘
                     │
                     │ HTTPS (via Cloudflare)
                     ▼
┌─────────────────────────────────────────────────┐
│   Cloudflare Tunnel (Zero Trust Network)        │
│   - Automatic SSL/TLS                           │
│   - WAF & DDoS Protection                       │
│   - Route optimization                          │
└────────────────────┬────────────────────────────┘
                     │
                     │ Secure tunnel over HTTPS
                     ▼
┌─────────────────────────────────────────────────┐
│        Your Server (Private Network)            │
│  ┌─────────────────────────────────────────┐   │
│  │  Nginx (Port 80)                        │   │
│  │  - Reverse proxy                        │   │
│  │  - Static file serving                  │   │
│  │  - Load balancing                       │   │
│  └──────────┬──────────────────────────────┘   │
│             │                                   │
│  ┌──────────▼──────────────────────────────┐   │
│  │  PHP-FPM (Unix Socket)                  │   │
│  │  - Process PHP code                     │   │
│  │  - Dynamic content                      │   │
│  │  - Laravel application logic            │   │
│  └──────────┬──────────────────────────────┘   │
│             │                                   │
│  ┌──────────▼──────────────────────────────┐   │
│  │  MySQL Database                         │   │
│  │  - Store application data               │   │
│  │  - Query execution                      │   │
│  └─────────────────────────────────────────┘   │
│                                                 │
│  ┌─────────────────────────────────────────┐   │
│  │  Redis Cache (Optional)                 │   │
│  │  - Session storage                      │   │
│  │  - Cache management                     │   │
│  └─────────────────────────────────────────┘   │
└─────────────────────────────────────────────────┘
```

**Keuntungan Setup Ini:**
✅ No need for public IP
✅ No need for port forwarding
✅ No need for manual SSL cert management
✅ Built-in DDoS protection
✅ Global CDN
✅ SSL/TLS termination di Cloudflare
✅ Private tunnel ke server
✅ Zero trust security model

---

## 📚 Documentation Files

| File | Kapan Baca |
|------|-----------|
| **NGINX_SETUP_GUIDE.md** | Pertama kali setup - comprehensive step-by-step |
| **QUICK_REFERENCE.md** | Setiap hari - commands reference untuk maintenance |
| **TROUBLESHOOTING.md** | Ketika ada problem - solutions + FAQ |

---

## 🆘 Need Help?

### Common Issues & Solutions

**502 Bad Gateway Error**
→ Lihat TROUBLESHOOTING.md > "502 Bad Gateway Error"

**Permission Denied Errors**
→ Run: `sudo chown -R www-data:www-data /var/www/wisata-web`

**Cloudflare Tunnel Not Connecting**
→ Run: `sudo systemctl restart cloudflared` & check: `sudo journalctl -u cloudflared -f`

**Database Connection Error**
→ Check: `cat /var/www/wisata-web/.env | grep DB_`

**Nginx won't start**
→ Test: `sudo nginx -t` untuk see specific error

Untuk lebih lengkap, baca **TROUBLESHOOTING.md**

---

## 🚀 Next Steps After Setup

### 1. Configure SSL di Cloudflare
```
Cloudflare Dashboard > SSL/TLS > Full (Strict)
```

### 2. Enable WAF
```
Cloudflare Dashboard > Firewall > WAF > Enable
```

### 3. Setup Monitoring
```bash
# Log monitoring
sudo tail -f /var/log/nginx/access.log
sudo tail -f /var/log/nginx/error.log
tail -f /var/www/wisata-web/storage/logs/laravel.log
```

### 4. Setup Backups
```bash
# Create backup script
sudo nano /usr/local/bin/backup.sh

# Add to cron untuk daily backups
sudo crontab -e
0 2 * * * /usr/local/bin/backup.sh
```

### 5. Performance Optimization
```bash
cd /var/www/wisata-web
sudo -u www-data composer install --no-dev --optimize-autoloader
sudo -u www-data php artisan config:cache
sudo -u www-data php artisan route:cache
```

---

## 📞 Support Resources

- **Laravel Docs**: https://laravel.com/docs
- **Nginx Docs**: https://nginx.org/en/docs/
- **Cloudflare Tunnel Docs**: https://developers.cloudflare.com/cloudflare-one/connections/connect-apps/
- **PHP-FPM Docs**: https://www.php.net/manual/en/install.fpm.php

---

## ✅ Setup Verification Checklist

- [ ] Server setup & dependencies installed
- [ ] Laravel application uploaded & configured
- [ ] Nginx running & config valid
- [ ] PHP-FPM running & serving PHP
- [ ] Database migrations completed
- [ ] Cloudflare Tunnel authenticated & running
- [ ] DNS CNAME record pointing to tunnel
- [ ] SSL/TLS mode set di Cloudflare
- [ ] Application accessible via domain
- [ ] Logs monitored for errors
- [ ] Backups scheduled
- [ ] Performance optimized
- [ ] Security hardened

---

## 🎉 Selamat!

Setup Anda sudah complete! 🚀

Application sekarang:
- ✅ Running di Nginx (fast & efficient)
- ✅ Accessible via Cloudflare Tunnel (secure)
- ✅ Protected dengan Cloudflare WAF & DDoS
- ✅ SSL/TLS encrypted (automatic)
- ✅ CDN accelerated (global)
- ✅ Private network (no public IP needed)

---

**Last Updated**: 2025 April
**Laravel Version**: 11.x
**Nginx Version**: Latest stable
**PHP Version**: 8.2+
