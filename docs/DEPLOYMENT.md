# Deployment

The theme is deployed by uploading a zip through the WordPress admin. There is
no SSH or FTP access wired up, and no CI.

## Release

1. Make the change in `theme/amir-al-afia/`.
2. **Bump the version** in `theme/amir-al-afia/style.css` and `VERSION`, and add
   the entry to `CHANGELOG.md`. WordPress compares the `Version:` header when a
   zip is re-uploaded; if it has not changed, the installer reports the upload
   as identical to what is already installed and asks you to confirm a replace.
3. Build:

   ```bash
   python tools/build-theme.py
   ```

   This runs `tools/check-php.py` first and refuses to package if the PHP does
   not balance. It writes `dist/amir-al-afia.zip` containing a single
   `amir-al-afia/` directory, which is the shape the installer expects.
4. Commit, including the rebuilt zip, and tag:

   ```bash
   git commit -am "…"
   git tag v1.1.0
   git push origin main --tags
   ```
5. Upload at **Appearance → Themes → Add New → Upload Theme**, choose
   `dist/amir-al-afia.zip`, and confirm **Replace installed with uploaded**.
6. Load the front page and check the browser console.

## Why the zip is committed

`dist/amir-al-afia.zip` is tracked rather than ignored. It is the artifact that
is actually installed, and keeping it in the repo means any commit can be
deployed without a build step — which matters when deployment happens through a
browser on a machine that may not have the repo checked out.

Rebuild it in the same commit as the source change so the two never drift.
`tools/build-theme.py` is deterministic: `.gitattributes` pins source files to
LF, so the same commit produces the same archive on any platform.

## After a first install

On a fresh WordPress:

1. Activate the theme.
2. **Settings → Permalinks** — set *Post name*. Without a permalink structure,
   `/properties/` will not resolve. The theme flushes rewrite rules once per
   version, but it cannot invent a structure that has not been chosen.
3. **Appearance → Starter Content** → *Import starter content*. This downloads
   about 15 images, so give it a minute.
4. **Appearance → Menus** — optional; the theme ships a sensible default menu.

## Current environment

| | |
| --- | --- |
| Host | CloudPanel, nginx 1.21.4 |
| Path | `/home/amiralafia/htdocs/amiralafia.com` |
| PHP | 8.3, `memory_limit` 512M, `upload_max_filesize` 64M, `max_execution_time` 60 |
| Database | MySQL 8.0 |
| WordPress | 7.1 |
| Plugins | None active — Akismet and Hello Dolly are present but off |

The 60-second PHP time limit matters for the starter-content import, which
downloads images one at a time. The importer raises it with `set_time_limit(300)`,
which the host allows. If an import ever times out, run it again — it resumes,
because anything already imported is reused.

## Rolling back

Check out the tag you want, then upload its `dist/amir-al-afia.zip`:

```bash
git checkout v1.0.0 -- dist/amir-al-afia.zip
```

Content is unaffected by a theme rollback, with one exception: the custom post
types are registered by the theme, so a rollback to a version that did not
define them would hide that content until the newer theme is restored. It is
still in the database either way.

## What is not automated

- No staging site. Changes are verified on production, which is acceptable
  while the site is pre-launch and worth revisiting before it is not.
- No database backups configured. Set these up at the host before launch.
- No CI. `tools/check-php.py` runs at build time only; nothing runs on push.
