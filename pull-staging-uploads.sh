#!/usr/bin/env bash
# Pulls wp-content/uploads down from staging. Never touches production and
# never writes to staging.
#
#   ./pull-staging-uploads.sh            copy new and changed files (asks first)
#   ./pull-staging-uploads.sh --dry-run  list them, change nothing
#
# Files that exist locally but differ from staging's are overwritten; each one
# is copied to backups/uploads-before-pull-<stamp>/ first. Nothing local is
# deleted, so files that only exist locally stay.

set -euo pipefail

HOST="${SEVERUS_STAGING_HOST:-ykosinets@192.168.1.20}"
REMOTE_WP="${SEVERUS_STAGING_PATH:-/var/www/severus}"
ROOT="$(cd "$(dirname "$0")" && pwd)"

DRY=""
for arg in "$@"; do
  case "$arg" in
    --dry-run) DRY="yes" ;;
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

# Reads from the terminal rather than stdin, so a pipe can never answer for you.
confirm() {
  local answer=""
  printf '%s [y/N] ' "$1"
  { read -r answer </dev/tty; } 2>/dev/null || true
  case "$answer" in
    y|Y|yes|YES) ;;
    *) echo "→ cancelled, nothing changed"; exit 1 ;;
  esac
}

ssh_ready || exit 2

if ! ssh -o BatchMode=yes "$HOST" "test -f ${REMOTE_WP}/wp-load.php"; then
  if ssh -o BatchMode=yes "$HOST" "test -f ${REMOTE_WP}/www/wp-load.php"; then
    REMOTE_WP="${REMOTE_WP}/www"
  else
    echo "No wp-load.php under ${REMOTE_WP} or ${REMOTE_WP}/www on ${HOST}." >&2
    exit 2
  fi
fi

SRC="${HOST}:${REMOTE_WP}/wp-content/uploads/"
DEST="${ROOT}/wp-content/uploads/"
mkdir -p "$DEST"

# No -p/-o/-g: the server's www-data group and 664 modes mean nothing here.
RSYNC=(rsync -rltz --exclude '.DS_Store')

echo "→ comparing ${SRC} with wp-content/uploads/"
PLAN="$("${RSYNC[@]}" --dry-run --itemize-changes "$SRC" "$DEST")"
NEW="$(grep -c '^>f+' <<<"$PLAN" || true)"
CHANGED="$(grep '^>f' <<<"$PLAN" | grep -v '^>f+' | cut -d' ' -f2- || true)"
CHANGED_COUNT="$(grep -c . <<<"$CHANGED" || true)"

if [ "$NEW" -eq 0 ] && [ "$CHANGED_COUNT" -eq 0 ]; then
  echo "→ already up to date"
  exit 0
fi

echo "→ ${NEW} new, ${CHANGED_COUNT} overwritten"
if [ "$CHANGED_COUNT" -gt 0 ]; then
  echo "  overwritten (local copy is backed up first):"
  head -n 30 <<<"$CHANGED" | sed 's/^/    /'
  [ "$CHANGED_COUNT" -gt 30 ] && echo "    … and $((CHANGED_COUNT - 30)) more"
fi

if [ -n "$DRY" ]; then
  echo "→ dry run, nothing written"
  exit 0
fi

confirm "Pull these files into wp-content/uploads?"

if [ "$CHANGED_COUNT" -gt 0 ]; then
  BACKUP="${ROOT}/backups/uploads-before-pull-$(date +%Y%m%d-%H%M%S)"
  echo "→ backing up overwritten files to backups/$(basename "$BACKUP")/"
  while IFS= read -r path; do
    [ -f "${DEST}${path}" ] || continue
    mkdir -p "${BACKUP}/$(dirname "$path")"
    cp -p "${DEST}${path}" "${BACKUP}/${path}"
  done <<<"$CHANGED"
fi

echo "→ syncing"
"${RSYNC[@]}" "$SRC" "$DEST"

echo "→ done"
