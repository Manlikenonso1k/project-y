#!/usr/bin/env bash
set -Eeuo pipefail

log "Step 40: Creating startup script and systemd service"

cat > "${START_SCRIPT}" <<EOF
#!/usr/bin/env bash
set -Eeuo pipefail

cd "${APP_DIR}"

fuser -k ${APP_PORT}/tcp || true

exec php artisan serve --host=127.0.0.1 --port=${APP_PORT}
EOF

run "chmod +x '${START_SCRIPT}'"

$SUDO bash -c "cat > '${SERVICE_FILE}'" <<EOF
[Unit]
Description=${PROJECT_NAME} Laravel + Tor
After=network-online.target tor.service
Wants=network-online.target
Requires=tor.service

[Service]
Type=simple
User=${RUN_USER}
WorkingDirectory=${APP_DIR}
ExecStart=/bin/bash ${START_SCRIPT}
Restart=always
RestartSec=5

[Install]
WantedBy=multi-user.target
EOF

run "$SUDO systemctl daemon-reload"
run "$SUDO systemctl enable ${PROJECT_NAME}.service"
run "$SUDO systemctl restart ${PROJECT_NAME}.service"
