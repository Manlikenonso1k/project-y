#!/usr/bin/env bash
set -Eeuo pipefail

log "Step 50: Reading onion hostname"
if [[ -f "${TOR_DIR}/hostname" ]]; then
  ONION_HOSTNAME="$($SUDO cat "${TOR_DIR}/hostname")"
  log "Onion URL: http://${ONION_HOSTNAME}"
else
  log "Onion hostname not ready yet. Check: $SUDO journalctl -u tor -n 100 --no-pager"
  exit 0
fi

if [[ -n "${BOT_TOKEN}" && -n "${CHAT_ID}" ]]; then
  log "Sending onion URL to Telegram"
  run "curl -sS -X POST 'https://api.telegram.org/bot${BOT_TOKEN}/sendMessage' -d 'chat_id=${CHAT_ID}' -d 'text=🧅 ${PROJECT_NAME} Onion URL: http://${ONION_HOSTNAME}' >/dev/null"
else
  log "Telegram credentials not set. Skipping notification."
fi
