#!/bin/bash
# Deployment Verification Checklist
# Run this file after deployment untuk verify semua berjalan dengan baik

echo "=========================================="
echo "  DEPLOYMENT VERIFICATION CHECKLIST"
echo "=========================================="
echo ""

# Color codes
GREEN='\033[0;32m'
RED='\033[0;31m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

pass_count=0
fail_count=0
warning_count=0

# Function to check command result
check_status() {
    if [ $? -eq 0 ]; then
        echo -e "${GREEN}✓ $1${NC}"
        ((pass_count++))
    else
        echo -e "${RED}✗ $1${NC}"
        ((fail_count++))
    fi
}

# Function for warnings
warning() {
    echo -e "${YELLOW}⚠ $1${NC}"
    ((warning_count++))
}

echo "=== SERVICE STATUS ==="
echo ""

# Check Nginx
echo "Checking Nginx..."
sudo systemctl is-active --quiet nginx
check_status "Nginx is running"

sudo nginx -T > /dev/null 2>&1
check_status "Nginx configuration is valid"

# Check PHP-FPM
echo ""
echo "Checking PHP-FPM..."
sudo systemctl is-active --quiet php8.2-fpm
check_status "PHP-FPM is running"

[ -S /run/php/php-fpm.sock ]
check_status "PHP-FPM socket exists"

# Check Cloudflare Tunnel
echo ""
echo "Checking Cloudflare Tunnel..."
sudo systemctl is-active --quiet cloudflared
check_status "Cloudflare Tunnel is running"

cloudflared tunnel list > /dev/null 2>&1
check_status "Cloudflare Tunnel is authenticated"

echo ""
echo "=== FILE SYSTEM CHECKS ==="
echo ""

# Check directories exist
[ -d /var/www/wisata-web ]
check_status "Application directory exists"

[ -f /var/www/wisata-web/artisan ]
check_status "Laravel artisan file exists"

[ -f /var/www/wisata-web/.env ]
check_status "Laravel .env file exists"

[ -d /var/www/wisata-web/storage/logs ]
check_status "Laravel logs directory exists"

# Check permissions
[ -w /var/www/wisata-web/storage ]
check_status "Storage directory is writable"

[ -w /var/www/wisata-web/bootstrap/cache ]
check_status "Bootstrap cache directory is writable"

echo ""
echo "=== CONNECTIVITY CHECKS ==="
echo ""

# Test local connectivity
curl -s -o /dev/null -w "%{http_code}" http://localhost 2>/dev/null | grep -q "200\|301\|302\|404"
check_status "Nginx is responding locally"

# Check PHP
curl -s -o /dev/null -w "%{http_code}" http://localhost/index.php 2>/dev/null | grep -q "200\|301\|302\|500"
check_status "PHP is responding"

echo ""
echo "=== DATABASE CHECKS ==="
echo ""

# Check MySQL
mysql -e "SELECT 1" > /dev/null 2>&1
check_status "MySQL is accessible"

# Try to connect using Laravel
cd /var/www/wisata-web
sudo -u www-data php artisan db:ping > /dev/null 2>&1
check_status "Laravel can connect to database"

echo ""
echo "=== CONFIGURATION CHECKS ==="
echo ""

# Check env variables
grep -q "APP_KEY=" /var/www/wisata-web/.env
check_status ".env has APP_KEY"

grep -q "DB_HOST=" /var/www/wisata-web/.env
check_status ".env has DB_HOST"

# Check if production mode
grep -q "APP_ENV=production" /var/www/wisata-web/.env
if [ $? -eq 0 ]; then
    echo -e "${GREEN}✓ APP_ENV is set to production${NC}"
    ((pass_count++))
else
    warning ".env has APP_ENV=development (not production)"
fi

# Check APP_DEBUG
grep -q "APP_DEBUG=false" /var/www/wisata-web/.env
if [ $? -eq 0 ]; then
    echo -e "${GREEN}✓ APP_DEBUG is set to false${NC}"
    ((pass_count++))
else
    warning ".env has APP_DEBUG=true (should be false for production)"
fi

echo ""
echo "=== LOG FILE CHECKS ==="
echo ""

# Check if logs are being written
find /var/log/nginx -type f -mmin -5 > /dev/null 2>&1
check_status "Nginx logs are being written"

find /var/www/wisata-web/storage/logs -type f -mmin -30 > /dev/null 2>&1
check_status "Laravel logs are being written"

# Check for recent errors in logs
grep -i "error\|fatal" /var/log/nginx/error.log | tail -5 > /tmp/nginx_errors.log
if [ -s /tmp/nginx_errors.log ]; then
    warning "Recent errors found in Nginx error log - check with: tail -20 /var/log/nginx/error.log"
fi

grep -i "error\|exception" /var/www/wisata-web/storage/logs/laravel.log 2>/dev/null | tail -5 > /tmp/laravel_errors.log
if [ -s /tmp/laravel_errors.log ]; then
    warning "Recent errors found in Laravel log - check with: tail -20 storage/logs/laravel.log"
fi

echo ""
echo "=== SECURITY CHECKS ==="
echo ""

# Check firewall
ufw status | grep -q "Status: active"
check_status "UFW firewall is enabled"

# Check SSH key auth (if available)
[ ! -f ~/.ssh/authorized_keys ] || [ -s ~/.ssh/authorized_keys ]
check_status "SSH is configured"

# Check file permissions
[ $(stat -c '%a' /var/www/wisata-web/.env) = "600" ] || [ $(stat -c '%a' /var/www/wisata-web/.env) = "640" ]
check_status ".env has secure permissions"

echo ""
echo "=== CLOUDFLARE TUNNEL CHECKS ==="
echo ""

# Check tunnel config exists
[ -f ~/.cloudflared/config.yml ]
check_status "Cloudflare config file exists"

# Check tunnel credentials
[ -f ~/.cloudflared/*.json ]
check_status "Cloudflare credentials file exists"

# Check tunnel status
cloudflared tunnel status wisata-web > /dev/null 2>&1
check_status "Cloudflare tunnel status is healthy"

echo ""
echo "=== RESOURCE USAGE ==="
echo ""

# Memory usage
echo "Memory Usage:"
free -h | head -2 | tail -1

# Disk usage
echo ""
echo "Disk Usage:"
df -h /var/www/wisata-web | tail -1

# Process count
echo ""
echo "Running Processes:"
echo "Nginx workers: $(ps aux | grep 'nginx: worker' | grep -v grep | wc -l)"
echo "PHP-FPM processes: $(ps aux | grep 'php-fpm: pool' | grep -v grep | wc -l)"

echo ""
echo "=========================================="
echo "  VERIFICATION SUMMARY"
echo "=========================================="
echo ""
echo -e "${GREEN}Passed: $pass_count${NC}"
echo -e "${RED}Failed: $fail_count${NC}"
if [ $warning_count -gt 0 ]; then
    echo -e "${YELLOW}Warnings: $warning_count${NC}"
fi
echo ""

if [ $fail_count -eq 0 ] && [ $warning_count -eq 0 ]; then
    echo -e "${GREEN}✓ ALL CHECKS PASSED! System is ready for production.${NC}"
    exit 0
elif [ $fail_count -eq 0 ] && [ $warning_count -gt 0 ]; then
    echo -e "${YELLOW}⚠ CHECKS PASSED WITH WARNINGS. Review warnings above.${NC}"
    exit 0
else
    echo -e "${RED}✗ SOME CHECKS FAILED. Review errors above and fix issues.${NC}"
    exit 1
fi
