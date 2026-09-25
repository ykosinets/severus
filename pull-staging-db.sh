#!/usr/bin/env bash
# Pulls the staging database down over the local one. Never touches production
# and never writes to staging.
#
#   ./pull-staging-db.sh            back up the local database, then replace it
#                                   with staging's (asks first)
#   ./pull-staging-db.sh --dry-run  show what it would do, change nothing
#
# The local database is REPLACED: anything edited locally since the last push
# is lost, so a dump of it is taken first and left in backups/, next to the
# staging dump that was imported. Media added on staging is not pulled here —
# that is pull-staging-uploads.sh.

set -euo pipefail

HOST="${SEVERUS_STAGING_HOST:-ykosinets@192.168.1.20}"
REMOTE_WP="${SEVERUS_STAGING_PATH:-/var/www/severus}"
DDEV="${DDEV_BIN:-/opt/homebrew/bin/ddev}"
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

# Always the ddev wp: the host's own wp CLI is wired to the production database.
LOCAL_URL="$("$DDEV" wp option get siteurl | tr -d '\r')"
[ -n "$LOCAL_URL" ] || { echo "Could not read the local siteurl from ddev." >&2; exit 2; }
STAGING_URL="$(ssh -o BatchMode=yes "$HOST" "cd ${REMOTE_WP} && wp option get siteurl" | tr -d '\r')"
[ -n "$STAGING_URL" ] || { echo "Could not read the staging siteurl." >&2; exit 2; }
LOCAL_HOST="${LOCAL_URL#*://}"
STAGING_HOST="${STAGING_URL#*://}"

# Imported tables keep staging's prefix; if wp-config expects another one the
# local site would come up as a fresh install.
LOCAL_PREFIX="$("$DDEV" wp db prefix | tr -d '\r')"
STAGING_PREFIX="$(ssh -o BatchMode=yes "$HOST" "cd ${REMOTE_WP} && wp db prefix" | tr -d '\r')"
if [ "$LOCAL_PREFIX" != "$STAGING_PREFIX" ]; then
  echo "Table prefixes differ: local ${LOCAL_PREFIX}, staging ${STAGING_PREFIX}." >&2
  exit 2
fi

echo "→ staging ${STAGING_URL} (${HOST}:${REMOTE_WP}) → local ${LOCAL_URL}"

if [ -n "$DRY" ]; then
  echo "→ dry run: would back up the local database, import staging's,"
  echo "  replace ${STAGING_HOST} with ${LOCAL_HOST}, and flush caches"
  exit 0
fi

echo "  The local database will be REPLACED. Local-only content, users and"
echo "  settings are lost; your local login becomes the staging one."
confirm "Pull the staging database?"

STAMP="$(date +%Y%m%d-%H%M%S)"
mkdir -p "${ROOT}/backups"
BACKUP="${ROOT}/backups/severus-local-before-pull-${STAMP}.sql.gz"
DUMP="${ROOT}/backups/severus-staging-${STAMP}.sql.gz"

echo "→ backing up local to backups/$(basename "$BACKUP")"
"$DDEV" export-db --file="$BACKUP" >/dev/null

echo "→ exporting the staging database"
ssh -o BatchMode=yes "$HOST" "cd ${REMOTE_WP} && wp db export - | gzip" > "$DUMP"
# The remote pipe hides a failed export behind gzip, so check what arrived.
if ! gzip -t "$DUMP" 2>/dev/null || ! gunzip -c "$DUMP" | grep -q 'CREATE TABLE'; then
  echo "The staging dump is empty or broken; the local database was not touched." >&2
  rm -f "$DUMP"
  exit 1
fi

# Per-environment options: the ACF Pro licence is bound to the site URL and
# FormCanary identifies each install, so the local values survive the import.
KEEP="'acf_pro_license','acf_pro_license_status','formcanary_instance_id'"
"$DDEV" mysql -e "CREATE DATABASE IF NOT EXISTS pull_keep; DROP TABLE IF EXISTS pull_keep.options;
  CREATE TABLE pull_keep.options AS SELECT option_name, option_value FROM db.${LOCAL_PREFIX}options WHERE option_name IN (${KEEP});"

echo "→ importing it locally"
"$DDEV" import-db --file="$DUMP" >/dev/null
"$DDEV" mysql -e "UPDATE db.${LOCAL_PREFIX}options o JOIN pull_keep.options k USING(option_name) SET o.option_value = k.option_value;
  DROP DATABASE pull_keep;"
"$DDEV" wp search-replace "https://${STAGING_HOST}" "${LOCAL_URL}" --all-tables-with-prefix --precise --quiet
"$DDEV" wp search-replace "http://${STAGING_HOST}" "${LOCAL_URL}" --all-tables-with-prefix --precise --quiet
"$DDEV" wp search-replace "${STAGING_HOST}" "${LOCAL_HOST}" --all-tables-with-prefix --precise --quiet
"$DDEV" wp rewrite flush --quiet
"$DDEV" wp cache flush --quiet
"$DDEV" wp transient delete --all >/dev/null

echo "→ local siteurl: $("$DDEV" wp option get siteurl | tr -d '\r')"
echo "→ done. Staging dump kept at backups/$(basename "$DUMP")"
echo "  To undo: ddev import-db --file=backups/$(basename "$BACKUP")"
