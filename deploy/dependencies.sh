#!/usr/bin/env bash
set -Eeuo pipefail

# One-shot server bootstrap for production dependencies and Laravel optimization.
# Usage:
#   chmod +x dependencies.sh
#   ./dependencies.sh
#
# Optional environment overrides:
#   APP_DIR=/var/www/ecommerce-laravel PHP_VERSION=8.3 ENABLE_CLEARNET_TLS=false ./dependencies.sh
#   APT_UPDATE_IF_NEEDED=true ./dependencies.sh
#   FORCE_APT_UPDATE=false ./dependencies.sh

APP_DIR="${APP_DIR:-/var/www/ecommerce-laravel}"
PHP_VERSION="${PHP_VERSION:-8.3}"
ENABLE_CLEARNET_TLS="${ENABLE_CLEARNET_TLS:-false}"
APT_UPDATE_IF_NEEDED="${APT_UPDATE_IF_NEEDED:-true}"
FORCE_APT_UPDATE="${FORCE_APT_UPDATE:-false}"

if ! command -v apt >/dev/null 2>&1; then
  echo "This script is intended for Ubuntu/Debian servers with apt." >&2
  exit 1
fi

if [[ $EUID -eq 0 ]]; then
  SUDO=""
else
  SUDO="sudo"
fi

run() {
  echo "[RUN] $*"
  eval "$@"
}

echo "==> Checking OS dependencies"
REQUIRED_PACKAGES=(
  tor
  nginx
  mysql-server
  "php${PHP_VERSION}-fpm" "php${PHP_VERSION}-cli" "php${PHP_VERSION}-mysql" "php${PHP_VERSION}-mbstring" "php${PHP_VERSION}-xml" "php${PHP_VERSION}-curl" "php${PHP_VERSION}-zip" "php${PHP_VERSION}-bcmath" "php${PHP_VERSION}-intl"
  composer
  unzip git curl
  fail2ban ufw unattended-upgrades
)

MISSING_PACKAGES=()
for pkg in "${REQUIRED_PACKAGES[@]}"; do
  if ! dpkg -s "$pkg" >/dev/null 2>&1; then
    MISSING_PACKAGES+=("$pkg")
  fi
done

if [[ "$FORCE_APT_UPDATE" == "true" ]]; then
  echo "==> Running apt update (forced)"
  run "$SUDO apt update"
elif [[ "${#MISSING_PACKAGES[@]}" -gt 0 && "$APT_UPDATE_IF_NEEDED" == "true" ]]; then
  echo "==> Running apt update (missing packages detected)"
  run "$SUDO apt update"
else
  echo "==> Skipping apt update (no missing packages or update disabled)"
fi

if [[ "${#MISSING_PACKAGES[@]}" -gt 0 ]]; then
  echo "==> Installing missing OS dependencies: ${MISSING_PACKAGES[*]}"
  run "$SUDO apt install -y ${MISSING_PACKAGES[*]}"
else
  echo "==> All required OS dependencies are already installed"
fi

if [[ "$ENABLE_CLEARNET_TLS" == "true" ]]; then
  OPTIONAL_TLS_PACKAGES=(certbot python3-certbot-nginx)
  MISSING_TLS_PACKAGES=()

  for pkg in "${OPTIONAL_TLS_PACKAGES[@]}"; do
    if ! dpkg -s "$pkg" >/dev/null 2>&1; then
      MISSING_TLS_PACKAGES+=("$pkg")
    fi
  done

  if [[ "${#MISSING_TLS_PACKAGES[@]}" -gt 0 ]]; then
    echo "==> Installing optional Certbot packages: ${MISSING_TLS_PACKAGES[*]}"
    run "$SUDO apt install -y ${MISSING_TLS_PACKAGES[*]}"
  else
    echo "==> Optional Certbot packages already installed"
  fi
fi

echo "==> Enabling core services"
run "$SUDO systemctl enable --now tor"
run "$SUDO systemctl enable --now nginx"
run "$SUDO systemctl enable --now mysql"
run "$SUDO systemctl enable --now php${PHP_VERSION}-fpm"
run "$SUDO systemctl enable --now fail2ban"
run "$SUDO systemctl enable --now unattended-upgrades"

echo "==> Basic firewall hardening (Tor-only baseline from SETUP.md)"
run "$SUDO ufw --force default deny incoming"
run "$SUDO ufw --force default allow outgoing"
run "$SUDO ufw allow OpenSSH"
run "$SUDO ufw --force enable"
run "$SUDO ufw status"

if [[ ! -d "$APP_DIR" ]]; then
  echo "Application directory not found: $APP_DIR"
  echo "Clone/copy your project there, then rerun this script."
  exit 0
fi

echo "==> Installing Laravel production dependencies"
cd "$APP_DIR"
run "composer install --no-dev --optimize-autoloader --classmap-authoritative"

echo "==> Running Laravel production commands"
run "php artisan migrate --force"
run "php artisan optimize:clear"
run "php artisan config:cache"
run "php artisan route:cache"
run "php artisan view:cache"
run "php artisan storage:link || true"

echo "==> Done"
echo "Next manual steps required (project-specific):"
echo "1) Set .env values (APP_ENV, APP_DEBUG, APP_URL, BLOCKONOMICS_*)."
echo "2) Configure /etc/tor/torrc hidden service mapping."
echo "3) Configure Nginx server block to listen on 127.0.0.1:8080 for onion-only hosting."
echo "4) Restart services: sudo systemctl restart tor nginx php${PHP_VERSION}-fpm"
