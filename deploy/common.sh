#!/usr/bin/env bash
set -Eeuo pipefail

log() {
  echo "[$(date +'%Y-%m-%d %H:%M:%S')] $*"
}

run() {
  log "RUN: $*"
  eval "$*"
}

require_linux_apt() {
  if ! command -v apt >/dev/null 2>&1; then
    echo "This deploy flow supports Ubuntu/Debian servers (apt) only." >&2
    exit 1
  fi
}

set_sudo() {
  if [[ ${EUID:-$(id -u)} -eq 0 ]]; then
    SUDO=""
  else
    SUDO="sudo"
  fi
}

load_defaults() {
  PROJECT_NAME="${PROJECT_NAME:-projectx}"
  REPO_URL="${REPO_URL:-https://github.com/Manlikenonso1k/project-x.git}"
  PROJECT_DIR="${PROJECT_DIR:-/var/www/${PROJECT_NAME}}"
  APP_DIR="${APP_DIR:-${PROJECT_DIR}/app}"
  SERVICE_FILE="${SERVICE_FILE:-/etc/systemd/system/${PROJECT_NAME}.service}"
  START_SCRIPT="${START_SCRIPT:-/home/${USER}/start_${PROJECT_NAME}.sh}"
  TOR_DIR="${TOR_DIR:-/var/lib/tor/${PROJECT_NAME}_hidden_service}"
  APP_PORT="${APP_PORT:-8001}"
  RUN_USER="${RUN_USER:-${USER}}"

  BOT_TOKEN="${BOT_TOKEN:-}"
  CHAT_ID="${CHAT_ID:-}"
}

print_summary() {
  log "PROJECT_NAME=${PROJECT_NAME}"
  log "PROJECT_DIR=${PROJECT_DIR}"
  log "APP_DIR=${APP_DIR}"
  log "REPO_URL=${REPO_URL}"
  log "TOR_DIR=${TOR_DIR}"
  log "APP_PORT=${APP_PORT}"
  log "RUN_USER=${RUN_USER}"
}
