#!/bin/bash

##########################################################
# Automated Deployment Script untuk Laravel + Nginx + Cloudflare Tunnel
# 
# Usage: sudo bash deploy.sh
# Requirements: Ubuntu/Debian system, sudo access
##########################################################

set -e  # Exit on error

# Color codes untuk output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Configuration
APP_DIR="/var/www/wisata-web"
APP_USER="www-data"
APP_GROUP="www-data"
PHP_VERSION="8.2"
NGINX_CONF_SOURCE="./nginx.conf"  # Path ke nginx.conf yang sudah dibuat
CLOUDFLARE_TUNNEL_NAME="wisata-web"
DOMAIN="wisata.yourdomain.com"

##########################################################
# Function Definitions
##########################################################

print_header() {
    echo -e "${BLUE}=== $1 ===${NC}"
}

print_success() {
    echo -e "${GREEN}✓ $1${NC}"
}

print_error() {
    echo -e "${RED}✗ $1${NC}"
}

print_warning() {
    echo -e "${YELLOW}⚠ $1${NC}"
}

check_root() {
    if [[ $EUID -ne 0 ]]; then
        print_error "Script ini harus dijalankan dengan sudo"
        exit 1
    fi
}

##########################################################
# Step 1: Update System
##########################################################
step_update_system() {
    print_header "Step 1: Update System"
    apt update && apt upgrade -y
    print_success "System updated"
}

##########################################################
# Step 2: Install Dependencies
##########################################################
step_install_dependencies() {
    print_header "Step 2: Install Dependencies"
    
    # Install Nginx
    print_warning "Installing Nginx..."
    apt install -y nginx
    systemctl enable nginx
    print_success "Nginx installed"
    
    # Install PHP
    print_warning "Installing PHP ${PHP_VERSION}..."
    apt install -y php${PHP_VERSION}-fpm php${PHP_VERSION}-cli php${PHP_VERSION}-common \
                   php${PHP_VERSION}-mysql php${PHP_VERSION}-mbstring php${PHP_VERSION}-xml \
                   php${PHP_VERSION}-curl php${PHP_VERSION}-bcmath php${PHP_VERSION}-gd \
                   php${PHP_VERSION}-json php${PHP_VERSION}-zip php${PHP_VERSION}-opcache
    systemctl enable php${PHP_VERSION}-fpm
    print_success "PHP installed"
    
    # Install Composer
    print_warning "Installing Composer..."
    curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
    print_success "Composer installed"
    
    # Install Git
    apt install -y git
    print_success "Git installed"
    
    # Install other utilities
    apt install -y curl wget nano htop
    print_success "Utilities installed"
}

##########################################################
# Step 3: Create Application Directory
##########################################################
step_create_app_directory() {
    print_header "Step 3: Create Application Directory"
    
    if [ ! -d "$APP_DIR" ]; then
        mkdir -p "$APP_DIR"
        print_success "Directory created: $APP_DIR"
    else
        print_warning "Directory already exists: $APP_DIR"
    fi
}

##########################################################
# Step 4: Setup Application
##########################################################
step_setup_application() {
    print_header "Step 4: Setup Laravel Application"
    
    cd "$APP_DIR"
    
    # Jika belum ada files, clone dari git atau upload via SCP
    if [ ! -f "$APP_DIR/artisan" ]; then
        print_warning "Laravel files tidak ditemukan di $APP_DIR"
        print_warning "Please:"
        print_warning "  1. Clone dari git: git clone <repo-url> $APP_DIR"
        print_warning "  2. Atau upload files via SCP"
        read -p "Tekan ENTER ketika selesai..."
    fi
    
    # Copy .env
    if [ ! -f "$APP_DIR/.env" ]; then
        print_warning "Copying .env.example to .env..."
        cp .env.example .env
        print_success ".env created"
    fi
    
    # Generate APP_KEY
    print_warning "Generating APP_KEY..."
    sudo -u www-data php artisan key:generate
    print_success "APP_KEY generated"
    
    # Install dependencies
    print_warning "Installing composer dependencies..."
    sudo -u www-data composer install --no-dev --optimize-autoloader
    print_success "Dependencies installed"
    
    # Storage link
    print_warning "Creating storage link..."
    sudo -u www-data php artisan storage:link
    print_success "Storage link created"
}

##########################################################
# Step 5: Set Permissions
##########################################################
step_set_permissions() {
    print_header "Step 5: Set Permissions"
    
    chown -R $APP_USER:$APP_GROUP "$APP_DIR"
    chmod -R 755 "$APP_DIR"
    chmod -R 775 "$APP_DIR/storage"
    chmod -R 775 "$APP_DIR/bootstrap/cache"
    chmod 600 "$APP_DIR/.env"
    
    print_success "Permissions set"
}

##########################################################
# Step 6: Configure Nginx
##########################################################
step_configure_nginx() {
    print_header "Step 6: Configure Nginx"
    
    # Copy nginx config
    if [ -f "$NGINX_CONF_SOURCE" ]; then
        cp "$NGINX_CONF_SOURCE" "/etc/nginx/sites-available/wisata-web"
        
        # Update app directory dalam nginx config
        sed -i "s|/var/www/wisata-web|$APP_DIR|g" "/etc/nginx/sites-available/wisata-web"
        
        # Disable default site
        rm -f /etc/nginx/sites-enabled/default
        
        # Enable site
        ln -sf /etc/nginx/sites-available/wisata-web /etc/nginx/sites-enabled/wisata-web
        
        # Test nginx
        print_warning "Testing Nginx configuration..."
        if nginx -t; then
            print_success "Nginx configuration valid"
            systemctl restart nginx
            print_success "Nginx restarted"
        else
            print_error "Nginx configuration tidak valid!"
            exit 1
        fi
    else
        print_error "nginx.conf tidak ditemukan di $NGINX_CONF_SOURCE"
        exit 1
    fi
}

##########################################################
# Step 7: Configure PHP-FPM
##########################################################
step_configure_php_fpm() {
    print_header "Step 7: Configure PHP-FPM"
    
    PHP_FPM_CONF="/etc/php/${PHP_VERSION}/fpm/pool.d/www.conf"
    
    # Backup original
    cp "$PHP_FPM_CONF" "$PHP_FPM_CONF.backup"
    
    # Update settings
    sed -i 's/;pm.max_children = 5/pm.max_children = 50/' "$PHP_FPM_CONF"
    sed -i 's/;pm.start_servers = 2/pm.start_servers = 10/' "$PHP_FPM_CONF"
    sed -i 's/;pm.min_spare_servers = 1/pm.min_spare_servers = 5/' "$PHP_FPM_CONF"
    sed -i 's/;pm.max_spare_servers = 3/pm.max_spare_servers = 20/' "$PHP_FPM_CONF"
    
    systemctl restart php${PHP_VERSION}-fpm
    print_success "PHP-FPM configured and restarted"
}

##########################################################
# Step 8: Install Cloudflare Tunnel
##########################################################
step_install_cloudflare_tunnel() {
    print_header "Step 8: Install Cloudflare Tunnel"
    
    # Download dan install
    print_warning "Downloading cloudflared..."
    curl -L --output cloudflared.deb https://github.com/cloudflare/cloudflared/releases/latest/download/cloudflared-linux-amd64.deb
    
    dpkg -i cloudflared.deb
    rm cloudflared.deb
    
    print_success "Cloudflare Tunnel installed"
}

##########################################################
# Step 9: Setup Cloudflare Credentials
##########################################################
step_setup_cloudflare() {
    print_header "Step 9: Setup Cloudflare Tunnel"
    
    print_warning "Anda perlu melakukan autentikasi dengan Cloudflare"
    print_warning "Jalankan command berikut secara manual:"
    print_warning "  cloudflared tunnel login"
    print_warning ""
    print_warning "Setelah itu, buat tunnel baru:"
    print_warning "  cloudflared tunnel create $CLOUDFLARE_TUNNEL_NAME"
    print_warning ""
    
    read -p "Tekan ENTER ketika selesai dengan setup Cloudflare..."
}

##########################################################
# Step 10: Run Database Migrations
##########################################################
step_run_migrations() {
    print_header "Step 10: Run Database Migrations"
    
    cd "$APP_DIR"
    
    print_warning "Running migrations..."
    sudo -u www-data php artisan migrate --force
    print_success "Migrations completed"
    
    print_warning "Seeding database..."
    sudo -u www-data php artisan db:seed --force
    print_success "Database seeded"
}

##########################################################
# Step 11: Optimize Laravel
##########################################################
step_optimize_laravel() {
    print_header "Step 11: Optimize Laravel"
    
    cd "$APP_DIR"
    
    sudo -u www-data php artisan config:cache
    sudo -u www-data php artisan route:cache
    sudo -u www-data php artisan view:cache
    sudo -u www-data php artisan optimize
    
    print_success "Laravel optimized"
}

##########################################################
# Step 12: Setup Firewall
##########################################################
step_setup_firewall() {
    print_header "Step 12: Setup Firewall (UFW)"
    
    if ! command -v ufw &> /dev/null; then
        apt install -y ufw
    fi
    
    ufw --force enable
    ufw default deny incoming
    ufw default allow outgoing
    ufw allow 22/tcp
    ufw allow 80/tcp
    ufw allow 443/tcp
    
    print_success "Firewall configured"
}

##########################################################
# Step 13: Create Log Directories
##########################################################
step_create_logs() {
    print_header "Step 13: Create Log Directories"
    
    mkdir -p /var/log/php-fpm
    touch /var/log/php-fpm/error.log
    touch /var/log/php-fpm/slow.log
    touch /var/log/php-fpm/access.log
    chown -R $APP_USER:$APP_GROUP /var/log/php-fpm
    
    mkdir -p /var/cache/php-fpm-opcache
    chown -R $APP_USER:$APP_GROUP /var/cache/php-fpm-opcache
    
    print_success "Log directories created"
}

##########################################################
# Summary
##########################################################
print_summary() {
    print_header "DEPLOYMENT SUMMARY"
    
    echo ""
    echo "✓ System updated dan dependencies terinstall"
    echo "✓ Application directory: $APP_DIR"
    echo "✓ Nginx configured dan running"
    echo "✓ PHP-FPM configured dan running"
    echo "✓ Database migrations completed"
    echo "✓ Laravel optimized untuk production"
    echo ""
    
    print_warning "NEXT STEPS:"
    echo "1. Configure Cloudflare Tunnel:"
    echo "   - Run: cloudflared tunnel login"
    echo "   - Run: cloudflared tunnel create $CLOUDFLARE_TUNNEL_NAME"
    echo "   - Update ~/.cloudflared/config.yml dengan domain Anda"
    echo ""
    
    echo "2. Setup DNS di Cloudflare Dashboard:"
    echo "   - CNAME: $DOMAIN -> <tunnel-id>.cfargotunnel.com"
    echo "   - Enable proxy (orange cloud)"
    echo ""
    
    echo "3. Copy config.yml ke: ~/.cloudflared/config.yml"
    echo ""
    
    echo "4. Start Cloudflare service:"
    echo "   sudo systemctl start cloudflared"
    echo "   sudo systemctl enable cloudflared"
    echo ""
    
    echo "IMPORTANT FILES & LOGS:"
    echo "  Laravel logs: $APP_DIR/storage/logs/laravel.log"
    echo "  Nginx logs: /var/log/nginx/"
    echo "  PHP-FPM logs: /var/log/php-fpm/"
    echo "  Cloudflare logs: sudo journalctl -u cloudflared -f"
    echo ""
    
    print_success "Deployment completed!"
}

##########################################################
# Main Execution
##########################################################
main() {
    print_header "LARAVEL + NGINX + CLOUDFLARE TUNNEL DEPLOYMENT"
    
    check_root
    
    read -p "Lanjutkan deployment? (y/n) " -n 1 -r
    echo
    if [[ ! $REPLY =~ ^[Yy]$ ]]; then
        exit 1
    fi
    
    step_update_system
    step_install_dependencies
    step_create_app_directory
    step_setup_application
    step_set_permissions
    step_configure_nginx
    step_configure_php_fpm
    step_create_logs
    step_install_cloudflare_tunnel
    step_setup_cloudflare
    step_run_migrations
    step_optimize_laravel
    step_setup_firewall
    
    print_summary
}

# Run main function
main
