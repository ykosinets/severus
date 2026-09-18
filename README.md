# severus.co

WordPress site. Only the active theme, `wp-content/themes/severus-noir`, and the
tooling below are tracked; core, plugins, uploads and the previous theme live in
the working copy but stay out of git.

## Local site

DDEV serves the site at <https://severus.ddev.site>.

```bash
ddev start
ddev wp option get siteurl        # any wp-cli command
```

The hostname needs one entry in `/etc/hosts`, added once (asks for a password):

```bash
sudo ddev hostname severus.ddev.site 127.0.0.1
```

## Theme

```bash
npm run build     # build the theme once
npm run dev       # rebuild on change
```

Both delegate to `wp-content/themes/severus-noir`, whose README covers the
component layout and the build.

## Deploying to staging

Staging is <https://severus.ykosinets.duckdns.org>. Production is never touched.

```bash
npm run deploy         # code, then database
npm run deploy:code    # build and rsync the theme
npm run deploy:db      # push the local database and new uploads
```

Flags go after `--`, e.g. `npm run deploy:code -- --dry-run`. `deploy:code`
also takes `--activate`; `deploy:db` takes `--no-uploads`.

`deploy:db` **replaces** the staging database, so anything edited in the staging
admin is lost. It dumps staging to `~/backups` on the server first, then imports
the local database and rewrites the local hostname (read from DDEV) to the
staging one.

Override the target with `SEVERUS_STAGING_HOST`, `SEVERUS_STAGING_PATH` and
`SEVERUS_STAGING_URL`.

The staging host is on the LAN, so ssh has to reach it. The terminal inside an
IDE may lack macOS's Local Network permission and fail with "No route to host"
while a normal terminal works.
