#!/usr/bin/env bash
set -Eeuo pipefail

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"

echo "Running modular deploy in one command..."
bash "${SCRIPT_DIR}/deploy_projectx.sh"