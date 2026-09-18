#!/usr/bin/env bash
# Ships the theme to staging. Never touches production.
#
#   ./deploy-staging.sh --dry-run     show what would change
#   ./deploy-staging.sh               copy the files
#   ./deploy-staging.sh --activate    copy, then switch the theme over
#
# The theme is built first, because assets/dist is gitignored and the server is
# not expected to run npm.

set -euo pipefail

HOST="${SEVERUS_STAGING_HOST:-ykosinets@192.168.1.20}"
REMOTE_WP="${SEVERUS_STAGING_PATH:-/var/www/severus}"
THEME="severus-noir"
LOCAL="$(cd "$(dirname "$0")" && pwd)/wp-content/themes/${THEME}"

DRY=""
ACTIVATE=""
for arg in "$@"; do
  case "$arg" in
    --dry-run) DRY="--dry-run" ;;
    --activate) ACTIVATE="yes" ;;
    *) echo "unknown option: $arg" >&2; exit 2 ;;
  esac
done

# A failed ssh is not a bad remote path, so say so plainly — and give a short
# blip a couple of chances before giving up.
ssh_ready() {
  for attempt in 1 2 3; do
    if ssh -o BatchMode=yes -o ConnectTimeout=8 "$HOST" true 2>/dev/null; then
      return 0
    fi
    [ "$attempt" -lt 3 ] && echo "→ ${HOST} did not answer, retrying ($attempt/3)" && sleep 3
  done
  echo "Cannot reach ${HOST} over ssh." >&2
  ssh -o BatchMode=yes -o ConnectTimeout=8 "$HOST" true 2>&1 | sed 's/^/  /' >&2
  echo "  Set SEVERUS_STAGING_HOST to override the address." >&2
  return 1
}

ssh_ready || exit 2

# The docroot may sit a level below the account root, as it does locally, so
# find wp-load.php rather than assuming.
if ! ssh -o BatchMode=yes "$HOST" "test -f ${REMOTE_WP}/wp-load.php"; then
  if ssh -o BatchMode=yes "$HOST" "test -f ${REMOTE_WP}/www/wp-load.php"; then
    REMOTE_WP="${REMOTE_WP}/www"
  else
    echo "No wp-load.php under ${REMOTE_WP} or ${REMOTE_WP}/www on ${HOST}." >&2
    echo "Point SEVERUS_STAGING_PATH at the WordPress root." >&2
    exit 2
  fi
fi
echo "→ WordPress root: ${REMOTE_WP}"

# Screenshots and scratch images have reached staging this way before.
STRAY="$(find "$LOCAL" -maxdepth 2 \( -name '*.png' -o -name '*.jpg' -o -name '*.jpeg' \) -not -path '*/assets/*' -not -path '*/node_modules/*')"
if [ -n "$STRAY" ]; then
  echo "Stray images in the theme — remove them or move them under assets/:" >&2
  echo "$STRAY" >&2
  exit 2
fi

echo "→ building"
( cd "$LOCAL" && npm run build >/dev/null )

echo "→ syncing ${THEME} to ${HOST}:${REMOTE_WP}/wp-content/themes/"
rsync -az --delete $DRY \
  --exclude 'node_modules/' \
  --exclude '.build/' \
  --exclude '*.zip' \
  --exclude '.gitignore' \
  --exclude '.DS_Store' \
  --itemize-changes \
  "${LOCAL}/" "${HOST}:${REMOTE_WP}/wp-content/themes/${THEME}/"

if [ -n "$DRY" ]; then
  echo "→ dry run, nothing written"
  exit 0
fi

if [ -n "$ACTIVATE" ]; then
  echo "→ activating (previous theme is printed so it can be switched back)"
  ssh "$HOST" "cd ${REMOTE_WP} && wp theme list --status=active --field=name && wp theme activate ${THEME}"
fi

echo "→ done"
