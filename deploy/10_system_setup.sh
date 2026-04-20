#!/usr/bin/env bash
set -Eeuo pipefail

log "Step 10: Installing system dependencies"
run "$SUDO apt update"
run "$SUDO apt install -y git curl unzip composer tor nginx mysql-server php php-cli php-fpm php-mbstring php-xml php-curl php-zip php-bcmath"
