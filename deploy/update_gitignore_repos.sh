#!/usr/bin/env bash
set -euo pipefail

# Run this in Git Bash on Windows.
# It updates .gitignore for project-x and ErrandBridge, commits, and pushes.

repos=(
  "C:/Users/MUSIC MAN/Documents/project-x"
  "C:/Users/MUSIC MAN/Documents/ErrandBridge"
)

for repo in "${repos[@]}"; do
  if [ -d "$repo" ]; then
    echo "\n==> Processing $repo"
    cd "$repo"
    echo "Current .gitignore (first 100 lines):"
    sed -n '1,100p' .gitignore || true

    cat >> .gitignore <<'EOF'
.env
.env.local
.env.*.local
config/php.ini
auth.json
EOF

    git add .gitignore
    if git diff --staged --quiet; then
      echo "No changes to commit in $repo"
    else
      git commit -m "Secure environment files - add to .gitignore"
      git push origin main
    fi
  else
    echo "Directory not found: $repo"
  fi
done

echo "\nDone. Review each repo to confirm changes."