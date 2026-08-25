# Design review

A pass over the supplied mockup (`design/index.html`, `design/css/style.css`,
`design/js/main.js`) before it was rebuilt as a WordPress theme. Everything
below was found in the mockup as delivered; each entry says what was done.

The mockup's visual language — the navy/cyan palette, Barlow Condensed
headlines, the collage hero, the card grid — was kept as-is. The changes are
about correctness, not taste.

---

## Content that was wrong

### The Oman photography showed other countries

The six "Attractions & Nature of Oman" tiles pointed at Unsplash photo IDs that
do not show what their captions claim:

| Tile caption | What the ID actually renders |
| --- | --- |
| Stunning Beaches | A city skyline |
| Historical House | The Great Sphinx of Giza — Egypt |
| Sultan Qaboos Mosque | A man in a suit |
| Desert Adventures | Monument Valley — USA |
| Muttrah Souq | A waterfall |
| Mountain Scenery | A lone acacia tree |

Two of the six were the wrong country entirely, on the page of an Omani agency.

**Done:** replaced with six CC0 / Public Domain photographs, each opened and
checked to be the place it is captioned as — Muttrah Corniche, traditional
Muttrah houses, the Sultan Qaboos Grand Mosque courtyard, a Wahiba Sands camp,
Wadi Shab and Jebel Shams. Sources are listed in `inc/demo-content.php`.

Two tiles also reused the same photo ID, so the grid showed one image twice.

### A six-bedroom villa priced at $16,000, badged "For Sale"

Card 3 listed a 6-bed / 6-bath / 6,700 sqft villa in Qurum at $16,000 with a
**For Sale** badge. That reads as an annual rent that lost its badge.

**Done:** listings are database records now, so the badge is derived from the
property's Deal taxonomy and can never disagree with the price. The demo data
prices the Qurum villa realistically and puts the low-priced entries under
For Rent.

### `%98` satisfaction

The third hero stat read `%98`. **Done:** `98%`, and all three stats are
Customizer fields.

### A hardcoded 2024 copyright

**Done:** the year is generated, and the name comes from the site title.

## Markup that did not work

### The page loader had no logo

`#page-loader` contained an empty `<div class="">` and an empty comment where
the hexagon SVG should have been, so the loading screen showed a bare wordmark.

**Done:** the loader was removed rather than repaired. It held the page behind
a fixed overlay for a fixed 1,750 ms regardless of how fast the page was ready,
which directly inflates Largest Contentful Paint for no benefit. The brand mark
it needed now exists as `inc/logo-mark-inline.svg` if a loader is ever wanted.

### Hero content depended on JavaScript to appear

`.sr` elements were hidden in CSS (`opacity: 0`) and only revealed by
`revealHero()` on a timer, so a JavaScript failure left a blank hero
permanently.

**Done:** inverted. Nothing is hidden until `main.js` adds `sr-armed` to the
root element, so the page is fully readable with the script blocked. Reveal is
also skipped entirely under `prefers-reduced-motion`.

### Two menu items pointed at the same anchor

"Projects" and "Trading" both linked to `#investors`, so one of them silently
did nothing.

**Done:** the fallback menu gives every entry a distinct destination that
exists on the page, and the menu is a registered WordPress menu, so the labels
and targets are editable under **Appearance → Menus**.

### Filters were decorative

`initFilters()` moved the active highlight and nothing else — every filter
combination showed the same four cards.

**Done:** the buttons re-query the database over AJAX. They are rendered as
links to the corresponding archive, so they still filter with JavaScript off.

### The contact form submitted nowhere

There was no `<form>` element. The button toggled a success message that was
always true, on a page that had already discarded the input.

**Done:** a real form posting to `admin-post.php`, with a nonce, a honeypot,
a per-IP rate limit, a stored Lead record and an email to the office. `main.js`
upgrades it to `fetch()` when available.

### Dead buttons and unclickable cards

Team WhatsApp and Telegram controls were `<button>` elements with no handler.
Property cards and attraction tiles had `cursor: pointer` and no destination.

**Done:** all are real links now — `wa.me` / `t.me` for the team, the listing
permalink for a card, an optional URL for an attraction tile.

## CSS problems

### Hover looked identical to selected

`.filter-btn:hover` and `.filter-btn.active` shared one rule, so hovering any
button made it look like the current filter.

**Done:** hover is an outline change, selected is the filled navy pill.

### The collage grid overlapped itself

`.hcell-4` occupied columns 2–4 of rows 2–3 while `.hcell-8` claimed column 3
of row 3 — the same cell. Grid resolved it by pushing an item out of place.

**Done:** the grid areas are laid out without collisions. Cell 4's span leaves
room for eight photos, not nine, so the ninth slot was dropped.

### Conflicting collage sizing under 480px

The mobile rule set `height: 200px` on a grid whose rows were already fixed at
`88px × 4`, which the row template wins — the declared height did nothing.

**Done:** the phone breakpoint switches to a single full-width cell.

### Fixed pixel heights on images

`.prop-img-wrap { height: 180px }` and `.team-photo { height: 420px }` do not
adapt. **Done:** `aspect-ratio`, so cards stay proportional at any width.

## Accessibility

- Contrast: `--text-muted` was `#6B7280` on white (4.3:1, below AA for body
  text) and the cyan headline colour `#00CDDE` was 1.9:1. Muted text is now
  `#5A6473`, and headline cyan is `#0097A4` — the display cyan is kept for
  large decorative use. The WhatsApp green and Telegram blue were darkened so
  white label text passes.
- Every section had `aria-label` but no programmatic heading association.
  **Done:** `aria-labelledby` pointing at the real heading.
- The hero stats are a `<dl>`, the badge and price are real text, and the
  vertical attraction labels remain readable text rather than images.
- Added a skip link, visible focus rings, `aria-expanded` on the menu toggle,
  Escape to close the mobile menu, and `aria-live` on the results grid and the
  form feedback.
- Images carry meaningful alt text, and decorative ones carry empty alt.

## Performance

- Google Fonts requested eight weights across three subsets. **Done:**
  self-hosted, latin only, six faces (~134 KB), with the two above-the-fold
  faces preloaded.
- All photography was hotlinked from Unsplash's CDN. **Done:** imported into the
  Media Library, served from the site's own domain, with WordPress generating
  responsive `srcset`.
- Block-library CSS, `global-styles`, the emoji script and generator meta are
  dequeued on the front end — the theme renders no blocks there.

## Deliberately not changed

- The layout, palette, type scale and section order are the mockup's.
- Hero copy, the "Why Oman" claims and the agent names are the mockup's demo
  text. They are Customizer fields now, but the wording is unverified marketing
  copy and should be confirmed before launch — particularly the tax, residency
  and growth figures in the "Why Oman" cards.
- The demo property photography is Unsplash placeholder material. It should be
  replaced with real listing photos.
