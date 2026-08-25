# Architecture

## The constraint

No plugins, no parent theme. Everything the site does — listings, filtering,
lead capture, SEO tags — is theme code. That is unusual for WordPress and it
shapes most of the decisions below.

The trade-off is deliberate: nothing to keep updated, nothing to conflict,
nothing that stops working when a licence lapses. The cost is that the theme
carries responsibilities a plugin would normally own, so **content types live in
the theme and would disappear if the theme were swapped**. That is acceptable
here because the theme *is* the site.

## File map

```
theme/amir-al-afia/
├── style.css                 WordPress theme header only — styles are elsewhere
├── theme.json                Editor palette and type; front end uses main.css
├── functions.php             Bootstrap: supports, assets, head cleanup
│
├── inc/
│   ├── helpers.php           Phone/WhatsApp/Telegram URLs, price format, logo
│   ├── icons.php             The inline SVG icon set
│   ├── nav-fallback.php      Default menus until one is assigned in the admin
│   ├── post-type-property.php    Property CPT, taxonomies, admin columns
│   ├── meta-property.php         Property Details meta box and save handler
│   ├── post-type-agent.php       Agent CPT
│   ├── post-type-attraction.php  Attraction CPT
│   ├── post-type-lead.php        Lead CPT (private) and the unread bubble
│   ├── customizer.php        Every editable string and image
│   ├── lead-form.php         Validation, storage, notification, both endpoints
│   ├── property-query.php    Listing queries and the filter AJAX endpoint
│   ├── seo.php               Meta description, Open Graph, JSON-LD
│   ├── demo-content.php      Appearance → Starter Content
│   └── *-inline.svg          The brand logo and mark
│
├── template-parts/
│   ├── home/                 One file per landing-page section
│   └── property/             card.php, empty.php
│
├── front-page.php            Assembles the landing page from its sections
├── archive-property.php      /properties/ — also used by the three taxonomies
├── single-property.php       One listing, with specs, gallery and related
├── header.php  footer.php  index.php  page.php  search.php  404.php
│
└── assets/
    ├── css/main.css          The whole front end
    ├── css/admin.css         The meta boxes
    ├── js/main.js            Progressive enhancement only
    ├── js/admin.js           The gallery media picker
    ├── fonts/                Six self-hosted woff2 faces + fonts.css
    └── img/                  Logo, placeholder, team photo
```

## Data model

| Post type | Public | Purpose |
| --- | --- | --- |
| `property` | yes | Listings. Archive at `/properties/`, single page per listing. |
| `agent` | no | Team members. Rendered on the home page only. |
| `attraction` | no | The Oman tiles. Rendered on the home page only. |
| `aaa_lead` | no | Contact-form submissions. `create_posts` is denied, so they can only arrive through the form. |

Property meta is a flat set of `_aaa_*` keys, registered with
`register_post_meta()` so the REST API can read them, and written by one meta
box rather than a field plugin. Taxonomies: `property_type`, `deal_type`,
`property_location`. The first two seed fixed terms on activation so the filter
bar is never empty.

## Two decisions worth explaining

### Filters are links that JavaScript intercepts

Each filter button is an `<a>` pointing at the archive or a taxonomy term. With
JavaScript disabled, clicking one loads a real filtered page. With JavaScript,
`main.js` cancels the navigation and swaps the grid via `admin-ajax.php`, which
re-renders the same `template-parts/property/card.php` server-side — so the
markup can never drift between the two paths.

If the AJAX request fails for any reason other than being superseded, the
script falls back to following the link.

The featured flag decides what the *unfiltered* teaser shows. As soon as a
filter is active it stops applying, because a visitor asking for rentals wants
rentals, not the intersection of rentals and whatever the office flagged.

### Nothing is hidden until the script says so

Scroll-reveal styles are scoped under `.sr-armed`, a class only `main.js` adds.
The page therefore renders complete without JavaScript, and the animation is an
addition rather than a dependency. The same rules are neutralised under
`prefers-reduced-motion`.

This inverts the mockup, where elements were hidden by default and a script
failure left the hero permanently blank.

## Request path

The theme adds no queries to a normal page load beyond the content itself.
Front-end assets are one stylesheet and one deferred script; `wp-block-library`,
`global-styles`, `classic-theme-styles` and the emoji script are dequeued
because the front end renders no blocks.

Fonts are self-hosted with `font-display: swap`, and the two faces that paint
above the fold — Barlow Condensed 900 for the headline, Barlow 400 for body —
are preloaded from `wp_head` at priority 1.

## Security

- Both lead endpoints verify a nonce, and the AJAX filter verifies its own.
- The lead form has a honeypot field and a one-minute per-IP transient limit.
- Meta box saves check the nonce, autosave and `current_user_can( 'edit_post' )`.
- Output is escaped at the point of echo. The only unescaped output is the
  theme's own inline SVG files, which are static and authored here.
- `aaa_prune_stale_demo_media()` is the one destructive routine. It is scoped to
  attachments carrying a `_aaa_demo_key` this importer wrote, and only those
  whose key has left the set — media the office uploaded is never considered.

## Build

There is no PHP runtime on the build machine, so `tools/check-php.py` tokenises
each file well enough to skip strings, comments and heredocs, then reports
unbalanced brackets and unterminated literals. It is not a parser and it is no
substitute for `php -l`; it catches the class of mistake a hand-edited template
actually makes. `tools/build-theme.py` runs it before packaging and refuses to
build if it fails.
