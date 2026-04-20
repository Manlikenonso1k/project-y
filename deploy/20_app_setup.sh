#!/usr/bin/env bash
set -Eeuo pipefail

log "Step 20: Preparing project directory and application"

SCRIPT_REPO_DIR="$(cd "${SCRIPT_DIR}/.." && pwd)"
SOURCE_DIR=""

if [[ -n "${LOCAL_SOURCE_DIR}" ]]; then
  SOURCE_DIR="${LOCAL_SOURCE_DIR}"
elif [[ -d "${SCRIPT_REPO_DIR}/.git" && -f "${SCRIPT_REPO_DIR}/artisan" ]]; then
  SOURCE_DIR="${SCRIPT_REPO_DIR}"
fi

if [[ "${USE_LOCAL_SOURCE}" == "local" && -z "${SOURCE_DIR}" ]]; then
  echo "USE_LOCAL_SOURCE=local was requested but no LOCAL_SOURCE_DIR was found." >&2
  echo "Set LOCAL_SOURCE_DIR in deploy/config.env or run from a cloned repository." >&2
  exit 1
fi

run "$SUDO mkdir -p '${PROJECT_DIR}'"
run "$SUDO chown -R '${RUN_USER}:${RUN_USER}' '${PROJECT_DIR}'"

if [[ ! -d "${APP_DIR}/.git" ]]; then
  if [[ -n "${SOURCE_DIR}" && "${USE_LOCAL_SOURCE}" != "remote" ]]; then
    log "Using local source repository: ${SOURCE_DIR}"
    run "git clone '${SOURCE_DIR}' '${APP_DIR}'"
  else
    log "Cloning repository into ${APP_DIR} from remote"
    run "git clone '${REPO_URL}' '${APP_DIR}'"
  fi
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
