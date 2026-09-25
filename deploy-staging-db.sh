#!/usr/bin/env bash
# Pushes the local database (and, unless told not to, new uploads) to staging.
# Never touches production.
#
#   ./deploy-staging-db.sh              back up staging, then replace its
#                                       database with the local one
#   ./deploy-staging-db.sh --no-uploads leave wp-content/uploads alone
#   ./deploy-staging-db.sh --dry-run    show what it would do, change nothing
#
# Staging's database is REPLACED: anything edited in the staging admin since
# the last push is lost, so a dump of it is taken first and left in
# ~/backups on the server.

set -euo pipefail

HOST="${SEVERUS_STAGING_HOST:-ykosinets@192.168.1.20}"
REMOTE_WP="${SEVERUS_STAGING_PATH:-/var/www/severus}"
STAGING_URL="${SEVERUS_STAGING_URL:-https://severus.ykosinets.duckdns.org}"
DDEV="${DDEV_BIN:-/opt/homebrew/bin/ddev}"
ROOT="$(cd "$(dirname "$0")" && pwd)"

UPLOADS="yes"
DRY=""
for arg in "$@"; do
  case "$arg" in
    --no-uploads) UPLOADS="" ;;
    --dry-run) DRY="yes" ;;
    *) echo "unknown option: $arg" >&2; exit 2 ;;
  esac
done

LOCAL_URL="$("$DDEV" wp option get siteurl | tr -d '\r')"
[ -n "$LOCAL_URL" ] || { echo "Could not read the local siteurl from ddev." >&2; exit 2; }
LOCAL_HOST="${LOCAL_URL#*://}"
STAGING_HOSTNAME="${STAGING_URL#*://}"

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

if ! ssh -o BatchMode=yes "$HOST" "test -f ${REMOTE_WP}/wp-load.php"; then
  if ssh -o BatchMode=yes "$HOST" "test -f ${REMOTE_WP}/www/wp-load.php"; then
    REMOTE_WP="${REMOTE_WP}/www"
  else
    echo "No wp-load.php under ${REMOTE_WP} or ${REMOTE_WP}/www on ${HOST}." >&2
    exit 2
  fi
fi

echo "→ local ${LOCAL_URL} → staging ${STAGING_URL} (${HOST}:${REMOTE_WP})"

if [ -n "$DRY" ]; then
  echo "→ dry run: would back up staging, import the local database,"
  echo "  replace ${LOCAL_HOST} with ${STAGING_HOSTNAME}, and flush caches"
  [ -n "$UPLOADS" ] && rsync -az --no-perms --no-group --dry-run --itemize-changes --exclude '.DS_Store' \
    "${ROOT}/wp-content/uploads/" "${HOST}:${REMOTE_WP}/wp-content/uploads/"
  exit 0
fi

STAMP="$(date +%Y%m%d-%H%M%S)"
DUMP="/tmp/severus-local-${STAMP}.sql.gz"

echo "→ backing up staging to ~/backups/severus-staging-before-deploy-${STAMP}.sql.gz"
ssh -o BatchMode=yes "$HOST" "mkdir -p ~/backups && cd ${REMOTE_WP} && wp db export - | gzip > ~/backups/severus-staging-before-deploy-${STAMP}.sql.gz"

if [ -n "$UPLOADS" ]; then
  # PHP runs as www-data and writes here through the group, so the Mac's
  # 755/644 and gid must not reach the server: -a alone reset uploads to
  # read-only once, and gid 20 (staff) lands as dialout on Debian. The macOS
  # rsync ignores --chmod, so new entries are opened up afterwards instead.
  echo "→ syncing new uploads (nothing on the server is deleted)"
  rsync -az --no-perms --no-group --itemize-changes --exclude '.DS_Store' \
    "${ROOT}/wp-content/uploads/" "${HOST}:${REMOTE_WP}/wp-content/uploads/"
  ssh -o BatchMode=yes "$HOST" "cd ${REMOTE_WP}/wp-content/uploads \
    && find . -user \$(id -un) -type d ! -perm -2775 -exec chmod 2775 {} + \
    && find . -user \$(id -un) -type f ! -perm -664 -exec chmod ug+rw {} +"
fi

echo "→ exporting the local database"
"$DDEV" export-db --file="$DUMP" >/dev/null
trap 'rm -f "$DUMP"' EXIT

echo "→ importing it on staging"
scp -q "$DUMP" "${HOST}:backups/"
ssh -o BatchMode=yes "$HOST" "cd ${REMOTE_WP} \
  && gunzip -c ~/backups/$(basename "$DUMP") | wp db import - \
  && wp search-replace 'https://${LOCAL_HOST}' '${STAGING_URL}' --all-tables-with-prefix --precise --quiet \
  && wp search-replace 'http://${LOCAL_HOST}' '${STAGING_URL}' --all-tables-with-prefix --precise --quiet \
  && wp search-replace '${LOCAL_HOST}' '${STAGING_HOSTNAME}' --all-tables-with-prefix --precise --quiet \
  && wp rewrite flush && wp cache flush && wp transient delete --all >/dev/null \
  && rm -f ~/backups/$(basename "$DUMP") \
  && echo \"→ staging siteurl: \$(wp option get siteurl)\""

echo "→ done"
