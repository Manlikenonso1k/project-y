#!/usr/bin/env bash
set -Eeuo pipefail

log "Step 20: Preparing project directory and application"
run "$SUDO mkdir -p '${PROJECT_DIR}'"
run "$SUDO chown -R '${RUN_USER}:${RUN_USER}' '${PROJECT_DIR}'"

if [[ ! -d "${APP_DIR}/.git" ]]; then
  log "Cloning repository into ${APP_DIR}"
  run "git clone '${REPO_URL}' '${APP_DIR}'"
else
  log "Repository exists. Pulling latest changes."
  run "git -C '${APP_DIR}' fetch --all --prune"
  run "git -C '${APP_DIR}' pull --ff-only"
fi

cd "${APP_DIR}"
run "composer install --no-dev --optimize-autoloader"
run "cp -n .env.example .env || true"

if ! grep -qE '^APP_KEY=base64:' .env; then
  log "Generating APP_KEY"
  run "php artisan key:generate --force"
else
  log "APP_KEY already set. Skipping key generation."
fi

run "php artisan migrate --force || true"
run "php artisan optimize:clear"
