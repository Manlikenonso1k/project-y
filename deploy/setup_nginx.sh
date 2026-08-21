#!/usr/bin/env bash
set -euo pipefail

# ╔════════════════════════════════════════════════════════════════╗
# ║  PROJECT-X: CONVERT FROM ARTISAN SERVE TO NGINX               ║
# ║  This script fully configures your Tor .onion service         ║
# ║  for production use with Nginx + PHP-FPM                      ║
# ╚════════════════════════════════════════════════════════════════╝

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Configuration
PROJECT_ROOT="${PROJECT_ROOT:-.}"
PROJECT_USER="${PROJECT_USER:-www-data}"
PROJECT_GROUP="${PROJECT_GROUP:-www-data}"
ONION_ADDRESS="${ONION_ADDRESS:-}"
PHP_VERSION="${PHP_VERSION:-8.3}"

echo -e "${BLUE}╔════════════════════════════════════════════════════════════════╗${NC}"
echo -e "${BLUE}║  PROJECT-X: ARTISAN SERVE → NGINX CONVERSION                  ║${NC}"
echo -e "${BLUE}╚════════════════════════════════════════════════════════════════╝${NC}"
echo ""

# Check if running as root
if [[ $EUID -ne 0 ]]; then
   echo -e "${RED}Error: This script must be run as root${NC}"
   exit 1
fi

# Step 1: Update system packages
echo -e "${YELLOW}[1/9] UPDATE_SYSTEM_PACKAGES: Updating apt repositories and upgrading packages...${NC}"
apt-get update -qq
apt-get upgrade -y -qq

# Step 2: Install Nginx web server and PHP-FPM
echo -e "${YELLOW}[2/9] INSTALL_NGINX_AND_PHP_FPM: Installing web server and PHP FastCGI...${NC}"
apt-get install -y -qq nginx php${PHP_VERSION}-fpm php${PHP_VERSION}-cli php${PHP_VERSION}-mysql php${PHP_VERSION}-curl php${PHP_VERSION}-mbstring php${PHP_VERSION}-xml php${PHP_VERSION}-bcmath php${PHP_VERSION}-zip

# Step 3: Set project directory permissions
echo -e "${YELLOW}[3/9] SET_PROJECT_PERMISSIONS: Configuring file ownership and access rights...${NC}"
if [[ ! -d "$PROJECT_ROOT" ]]; then
    echo -e "${RED}Error: PROJECT_ROOT ($PROJECT_ROOT) does not exist${NC}"
    exit 1
fi
chown -R "$PROJECT_USER:$PROJECT_GROUP" "$PROJECT_ROOT"
chmod -R 755 "$PROJECT_ROOT"
chmod -R 775 "$PROJECT_ROOT/storage" "$PROJECT_ROOT/bootstrap/cache"

# Step 4: Create Nginx server block configuration
echo -e "${YELLOW}[4/9] CONFIGURE_NGINX_SERVER_BLOCK: Creating Nginx config for .onion domain...${NC}"
if [[ -z "$ONION_ADDRESS" ]]; then
    echo -e "${YELLOW}  No ONION_ADDRESS provided. Using default server block.${NC}"
    ONION_ADDRESS="localhost"
fi

cat > "/etc/nginx/sites-available/project-x.onion" <<EOF
server {
    listen 80;
    listen [::]:80;
    server_name $ONION_ADDRESS;

    root $PROJECT_ROOT/public;
    index index.php index.html;

    access_log /var/log/nginx/project-x.access.log;
    error_log /var/log/nginx/project-x.error.log;
    client_max_body_size 32m;

    location / {
        try_files \$uri \$uri/ /index.php?\$query_string;
    }

    location ~* \.(?:css|js|mjs|jpg|jpeg|gif|png|svg|webp|ico|ttf|otf|woff|woff2)\$ {
        expires 7d;
        add_header Cache-Control "public, max-age=604800";
        try_files \$uri /index.php?\$query_string;
    }

    location ~ \.php\$ {
        include fastcgi_params;
        fastcgi_param SCRIPT_FILENAME \$realpath_root\$fastcgi_script_name;
        fastcgi_param DOCUMENT_ROOT \$realpath_root;
        fastcgi_pass unix:/run/php/php${PHP_VERSION}-fpm.sock;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
EOF

# Remove default Nginx site if it exists
rm -f /etc/nginx/sites-enabled/default

# Enable the project site
if [[ ! -L /etc/nginx/sites-enabled/project-x.onion ]]; then
    ln -s /etc/nginx/sites-available/project-x.onion /etc/nginx/sites-enabled/project-x.onion
fi

# Test Nginx config
echo -e "${YELLOW}  Testing Nginx configuration...${NC}"
if ! nginx -t > /dev/null 2>&1; then
    echo -e "${RED}Error: Nginx configuration test failed${NC}"
    nginx -t
    exit 1
fi

# Step 5: Configure PHP-FPM pool settings
echo -e "${YELLOW}[5/9] CONFIGURE_PHP_FPM_POOL: Setting PHP-FPM user and group...${NC}"
php_fpm_config="/etc/php/${PHP_VERSION}/fpm/pool.d/www.conf"
if [[ -f "$php_fpm_config" ]]; then
    sed -i "s/^user = .*/user = $PROJECT_USER/" "$php_fpm_config"
    sed -i "s/^group = .*/group = $PROJECT_GROUP/" "$php_fpm_config"
fi

# Keep PHP-FPM's multipart limit aligned with the Nginx gift-card upload limit.
cat > "/etc/php/${PHP_VERSION}/fpm/conf.d/99-project-x-uploads.ini" <<EOF
upload_max_filesize = 12M
post_max_size = 32M
max_file_uploads = 10
max_execution_time = 120
max_input_time = 120
EOF

# Step 6: Enable services for auto-start on reboot
echo -e "${YELLOW}[6/9] ENABLE_SERVICES_ON_BOOT: Configuring systemd to start Nginx and PHP-FPM at boot...${NC}"
systemctl enable nginx
systemctl enable php${PHP_VERSION}-fpm
systemctl restart nginx
systemctl restart php${PHP_VERSION}-fpm

# Step 7: Build Vite frontend assets
echo -e "${YELLOW}[7/9] BUILD_VITE_ASSETS: Installing npm dependencies and building CSS/JS...${NC}"
cd "$PROJECT_ROOT"
if [[ -f "package.json" ]]; then
    npm install -q
    ./deploy/build_onion_assets.sh
fi

# Step 8: Clear Laravel application caches
echo -e "${YELLOW}[8/9] CLEAR_LARAVEL_CACHES: Flushing config, route, and view caches...${NC}"
cd "$PROJECT_ROOT"
if [[ -f "artisan" ]]; then
    php artisan cache:clear
    php artisan config:clear
    php artisan route:clear
fi

# Step 9: Verify conversion from artisan serve to Nginx
echo -e "${YELLOW}[9/9] VERIFY_NGINX_SETUP: Checking Nginx and PHP-FPM status...${NC}"
systemctl is-active --quiet nginx && echo -e "${GREEN}  ✓ Nginx is running${NC}" || echo -e "${RED}  ✗ Nginx failed to start${NC}"
systemctl is-active --quiet php${PHP_VERSION}-fpm && echo -e "${GREEN}  ✓ PHP-FPM is running${NC}" || echo -e "${RED}  ✗ PHP-FPM failed to start${NC}"

echo ""
echo -e "${GREEN}════════════════════════════════════════════════════════${NC}"
echo -e "${GREEN}✓ CONVERSION FROM ARTISAN SERVE TO NGINX COMPLETE!${NC}"
echo -e "${GREEN}════════════════════════════════════════════════════════${NC}"
echo ""
echo "📋 VERIFICATION & NEXT STEPS:"
echo ""
echo "  1️⃣  Verify Nginx status:"
echo "      sudo systemctl status nginx"
echo ""
echo "  2️⃣  Verify PHP-FPM status:"
echo "      sudo systemctl status php${PHP_VERSION}-fpm"
echo ""
echo "  3️⃣  Monitor application logs (live):"
echo "      sudo tail -f /var/log/nginx/project-x.error.log"
echo "      sudo tail -f /var/log/nginx/project-x.access.log"
echo ""
echo "  4️⃣  Services will AUTO-START on server reboot ✓"
echo ""
if [[ "$ONION_ADDRESS" != "localhost" ]]; then
    echo "  5️⃣  Access your site:"
    echo "      http://$ONION_ADDRESS"
fi
echo ""
echo "💡 STOP USING 'php artisan serve' - it's no longer needed!"
echo ""
