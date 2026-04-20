#!/usr/bin/env bash
set -Eeuo pipefail

log "Step 30: Configuring Tor hidden service"
run "$SUDO mkdir -p '${TOR_DIR}'"
run "$SUDO chown -R debian-tor:debian-tor '${TOR_DIR}'"
run "$SUDO chmod 700 '${TOR_DIR}'"

TOR_BLOCK_BEGIN="# BEGIN ${PROJECT_NAME}_hidden_service"
TOR_BLOCK_END="# END ${PROJECT_NAME}_hidden_service"
TOR_BLOCK_CONTENT="${TOR_BLOCK_BEGIN}\nHiddenServiceDir ${TOR_DIR}\nHiddenServicePort 80 127.0.0.1:${APP_PORT}\n${TOR_BLOCK_END}"

if ! $SUDO grep -q "${TOR_BLOCK_BEGIN}" /etc/tor/torrc; then
  log "Appending hidden service block to /etc/tor/torrc"
  run "printf '%b\n' \"${TOR_BLOCK_CONTENT}\" | $SUDO tee -a /etc/tor/torrc >/dev/null"
else
  log "Tor hidden service block already exists."
fi

run "$SUDO systemctl enable --now tor"
run "$SUDO systemctl restart tor"
