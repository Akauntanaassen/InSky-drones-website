#!/usr/bin/env bash
set -euo pipefail

ROOT="$(cd "$(dirname "$0")/.." && pwd)"
THEME="${1:-}"

if [[ "$THEME" != "light" && "$THEME" != "dark" ]]; then
  echo "Usage: $0 <light|dark>" >&2
  exit 1
fi

SRC="$ROOT/theme-variants/$THEME"
for f in index.html legal.html performance.html hevi.html applications.html styles.css; do
  cp "$SRC/$f" "$ROOT/$f"
done

echo "Applied theme: $THEME"
