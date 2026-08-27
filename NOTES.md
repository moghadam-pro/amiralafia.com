# Engineering notes

Working notes for this repository. Read `docs/ARCHITECTURE.md` for how the theme
is built and `docs/DESIGN-REVIEW.md` for why it departs from the mockup.

## The hard constraint

**No plugins, no parent theme.** This is a client requirement, not a
preference. Do not solve a problem by suggesting ACF, a listings plugin, an SEO
plugin or a form plugin. If something needs a plugin's capability, it gets
written into the theme.

## Before you change PHP

There is **no PHP runtime on this machine** — no `php`, no Docker, no WSL
distro. `php -l` is not available. Run the bracket checker instead:

```bash
python tools/check-php.py theme
```

It skips strings, comments and heredocs and reports unbalanced brackets. It is
not a parser. A syntax error it cannot see becomes a white screen on a live
site, so read what you wrote before shipping it.

`tools/build-theme.py` runs the checker and refuses to package on failure.

## Shipping a change

1. Edit under `theme/amir-al-afia/`.
2. Bump `Version:` in `theme/amir-al-afia/style.css` **and** `VERSION`, and add
   a `CHANGELOG.md` entry. WordPress compares that header on re-upload; an
   unchanged version makes the installer treat the zip as identical.
3. `python tools/build-theme.py`
4. Commit the source **and** the rebuilt `dist/amir-al-afia.zip` together. The
   zip is tracked on purpose — see `docs/DEPLOYMENT.md`.
5. Upload through **Appearance → Themes → Add New → Upload Theme**.

## Conventions

- **Prefix everything `aaa_`.** Functions, meta keys (`_aaa_price`), Customizer
  settings (`aaa_phone`), AJAX actions. Template-local variables too
  (`$aaa_price`), because `get_template_part()` shares scope with its caller
  and an unprefixed `$query` will collide.
- **WordPress coding standards**: tabs, Yoda conditions, `array()` not `[]`,
  spaces inside parentheses. Match the surrounding file.
- **Escape at the point of echo**, never earlier. `esc_html`, `esc_attr`,
  `esc_url`. The only unescaped output is the theme's own inline SVG files.
- **New editable text goes in the Customizer**, not a template. The office
  should never need a code change to reword a heading.
- **New markup that JavaScript enhances must work without it.** That is the
  pattern the filters and the contact form already follow; keep it.

## Front-end rules

- One stylesheet, one deferred script. Do not add a library.
- Nothing may be hidden by default and revealed by JavaScript. Reveal styles are
  scoped under `.sr-armed`, which only `main.js` adds.
- Respect `prefers-reduced-motion`.
- **Fonts are swappable at runtime.** `--display` and `--body` can be
  overridden inline by `inc/typography.php`, so never hardcode a family in a
  rule — always go through the two custom properties.
- **Icons are Heroicons v2 outline** (24x24, 1.5 stroke, round caps). New icons
  come from that set; the two documented exceptions are at the top of
  `inc/icons.php` and should stay short.
- Check contrast before putting text on `--primary`: white on `#018ED5` is
  3.60:1, which passes only for large text. Use `--primary-ink` otherwise.

## Regenerating the brand images

`screenshot.png` and `assets/img/share-default.png` are generated:

```bash
python tools/make-brand-images.py
```

Do not hand-edit them. They are drawn from the theme's own fonts and the
logo's path vertices so a palette change can be picked up by re-running — but
the vertices are a copy, so a new logo means editing `MARK_BACK` / `MARK_FRONT`
in that script as well as replacing the three SVGs.

## Things that will bite

- **`get_the_title()` on the front page** returns whatever the main query left
  behind, because `front-page.php` runs no loop. This produced leads labelled
  "Hello world!". Use an explicit post ID or the request URL.
- **Featured vs filtered.** `aaa_get_home_properties()` only prefers featured
  listings when no filter is active. Re-introducing the preference under a
  filter hides matching properties.
- **Rewrite rules** flush once per theme version via the `aaa_rewrites_flushed`
  option. Adding a post type or changing a slug needs a version bump to take
  effect, or a manual visit to Settings → Permalinks.
- **The AJAX filter renders the same template part** as the initial page load.
  Change `template-parts/property/card.php` and both paths follow; do not
  duplicate card markup in JavaScript.
- **`og:image:width` / `og:image:height` are load-bearing.** Drop them and
  WhatsApp and Facebook render a thumbnail instead of a large card. Equally,
  `wp_get_attachment_image_src()` falls back to the *full* image when the
  source is too small to crop, so the returned dimensions must be checked, not
  assumed — `aaa_share_image()` does both.
- **New image sizes are not retroactive.** WordPress only builds intermediate
  sizes at upload time, so adding an `add_image_size()` leaves every existing
  attachment without it. `aaa_regenerate_demo_sizes()` repairs the theme's own
  demo images; anything the office uploaded needs re-uploading.
- **Entity-encoded text must not go into JSON-LD.** `wp_get_document_title()`
  returns `&#8211;`, which is right in an attribute and wrong in a `<script>`.
  Run it through `aaa_schema_text()`.
- **Renaming a demo item in `aaa_demo_attractions()` creates a duplicate**,
  because the importer matches on slug. Delete the old post after a rename.
- **Never write `?>` inside a `//` comment.** PHP closes the tag there — it is
  not commented out — and everything after becomes page output, which fatals
  the whole site. Quoting a regex or some markup in a comment is how it
  happens. `tools/check-php.py` now catches it; `<?php // note ?>` on one line
  is still fine, because there the close is the point.

## Cloudflare caches static assets for ten years

`cache-control: max-age=315360000` on everything static. CSS and JS carry
`?ver=` from the theme version so they bust on a version bump; assets
referenced by a fixed filename do not. `share-default.png` is versioned in
`aaa_share_image()` because chat apps scrape that exact URL. Check the origin
with a cache-busting query before concluding a deploy did not land.

## Deployment access

Admin is reached through the browser at `https://amiralafia.com/wp-admin`. There
is no SSH or FTP. Deployment is a manual zip upload; there is no CI and no
staging site.
