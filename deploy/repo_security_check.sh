#!/usr/bin/env bash
set -euo pipefail

ROOT_DIR="$(CDPATH= cd -- "$(dirname -- "$0")/.." && pwd)"
cd "$ROOT_DIR"

REPO_NAME="${REPO_NAME:-$(basename "$ROOT_DIR")}"
REMOTE_NAME="${REMOTE_NAME:-origin}"
SSH_REMOTE_URL="${SSH_REMOTE_URL:-}"

echo "============================================================"
echo "Project Security Checklist"
echo "Repo: $REPO_NAME"
echo "============================================================"
echo

echo "Step 1/5: Update .gitignore"
echo "- Review the repository's .gitignore and add any missing secret files"
echo "- Typical Laravel entries: .env, .env.*, storage/*.key, *.pem, *.key, id_ed25519, id_rsa"
echo

echo "Step 2/5: Scan commit history for secrets"
if command -v trufflehog >/dev/null 2>&1; then
  echo "- Running trufflehog against git history"
  trufflehog git file://"$ROOT_DIR" --no-update
else
  echo "- trufflehog not found, using a quick git log scan instead"
  if git log -p --all | grep -Ei "password|secret|token|api[_-]?key|private[_-]?key|ssh-rsa|BEGIN [A-Z ]*PRIVATE KEY"; then
    echo "- Potential secrets found in history"
  else
    echo "- No obvious secrets found in the latest scan"
  fi
fi
echo

echo "Step 3/5: Configure SSH remote"
current_remote="$(git remote get-url "$REMOTE_NAME" 2>/dev/null || true)"
if [ -n "$SSH_REMOTE_URL" ]; then
  echo "- Setting $REMOTE_NAME to SSH URL: $SSH_REMOTE_URL"
  git remote set-url "$REMOTE_NAME" "$SSH_REMOTE_URL"
elif [ -n "$current_remote" ]; then
  case "$current_remote" in
    git@github.com:*)
      echo "- Remote already uses SSH: $current_remote"
      ;;
    https://github.com/*)
      echo "- Current remote is HTTPS: $current_remote"
      echo "- Re-run with SSH_REMOTE_URL=git@github.com:OWNER/REPO.git to convert it"
      ;;
    *)
      echo "- Remote detected: $current_remote"
      echo "- Re-run with SSH_REMOTE_URL set if you want to switch it to SSH"
      ;;
  esac
else
  echo "- No $REMOTE_NAME remote detected"
  echo "- Set SSH_REMOTE_URL to add or change the remote"
fi
echo

echo "Step 4/5: Disable GitHub Actions and Pages"
echo "- This cannot be done locally from git"
echo "- In GitHub, open Settings > Actions and Settings > Pages and disable them manually"
echo

echo "Step 5/5: Verify private status and commit history"
echo "- Recent commits:"
git log --oneline -5 || true
echo
echo "- Secret pattern check in recent history:"
if git log -p -5 | grep -Ei "password|secret|token|api[_-]?key|private[_-]?key"; then
  echo "- Review the matches above"
else
  echo "No obvious secrets found"
fi
echo
echo "Checklist complete."