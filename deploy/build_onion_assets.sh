#!/usr/bin/env sh
set -eu

# ╔════════════════════════════════════════════════════════════════╗
# ║  BUILD VITE ASSETS FOR TOR .ONION SERVICE                     ║
# ║  Run this EVERY TIME after pulling code changes               ║
# ╚════════════════════════════════════════════════════════════════╝

SCRIPT_DIR="$(CDPATH= cd -- "$(dirname -- "$0")" && pwd)"
PROJECT_ROOT="$(CDPATH= cd -- "$SCRIPT_DIR/.." && pwd)"

BASE_INPUT="${1:-./}"

case "$BASE_INPUT" in
  */) VITE_ASSET_BASE="$BASE_INPUT" ;;
  *) VITE_ASSET_BASE="$BASE_INPUT/" ;;
esac

cd "$PROJECT_ROOT"

export VITE_ASSET_BASE

echo "🔨 Building Vite assets with VITE_ASSET_BASE=$VITE_ASSET_BASE"
npm run build

echo "✓ Build complete. CSS/JS are in public/build/"
