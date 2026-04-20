#!/usr/bin/env bash
set -Eeuo pipefail

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
# shellcheck source=deploy/common.sh
source "${SCRIPT_DIR}/common.sh"

require_linux_apt
set_sudo
load_defaults

if [[ -f "${SCRIPT_DIR}/config.env" ]]; then
  log "Loading deploy/config.env"
  # shellcheck disable=SC1091
  source "${SCRIPT_DIR}/config.env"
fi

print_summary

log "Starting modular deployment"
# shellcheck source=deploy/10_system_setup.sh
source "${SCRIPT_DIR}/10_system_setup.sh"
# shellcheck source=deploy/20_app_setup.sh
source "${SCRIPT_DIR}/20_app_setup.sh"
# shellcheck source=deploy/30_tor_setup.sh
source "${SCRIPT_DIR}/30_tor_setup.sh"
# shellcheck source=deploy/40_service_setup.sh
source "${SCRIPT_DIR}/40_service_setup.sh"
# shellcheck source=deploy/50_notify.sh
source "${SCRIPT_DIR}/50_notify.sh"

log "Deployment complete"
log "Run this in one command: bash deploy/deploy_projectx.sh"
