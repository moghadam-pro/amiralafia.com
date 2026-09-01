# Changelog

All notable changes to the Amir Al Afia theme. The format follows
[Keep a Changelog](https://keepachangelog.com/en/1.1.0/), and the project uses
[Semantic Versioning](https://semver.org/spec/v2.0.0.html).

The version here is the same one in `theme/amir-al-afia/style.css` and
[VERSION](VERSION). WordPress compares that header when a theme zip is
re-uploaded, so it must be bumped on every release or the installer reports the
upload as identical to what is already there.

## [Unreleased]

## [1.8.3] - 2026-09-01

### Changed

- **No lapel pins on the team photograph.** They read as stuck-on at the size
  the photo actually displays. The pin machinery is gone from
  `make-team-photo.py` rather than left switched off.
- **Framed head to forearm**, the way the mockup's photograph was, instead of
  standing at mid-thigh.
- `.team-photo` is 11:10 rather than 4:5, and the mobile frame matches it
  instead of cropping to 16:9. The tighter crop cannot also be portrait: at
  that height 4:5 is 1070px wide against a 1396px-wide pair, so it would take
  163px off each of the outer arms. The frame follows the photograph.
- The image size is `aaa-team` at 880x800 - 2x the 420px column - replacing
  `aaa-portrait`. 113 KB.

## [1.8.2] - 2026-09-01

### Fixed

- The team photo was served as a 900x900 square. It was requested at
  `aaa-wide`, which is a 14:9 landscape crop — fine for an attraction banner
  or a listing gallery, wrong for two standing figures, and the frame then
  cropped the sides again to reach its own 4:5. It has its own `aaa-portrait`
  size now, 900x1125, matching the frame exactly so nothing is cut.

## [1.8.1] - 2026-09-01

### Changed

- **A new "Meet The Team" photograph.** The one carried over from the mockup
  belonged to another agency's website and could not be used. The replacement
  is a commissioned studio shot of two consultants in tailored suits, cut out
  against transparency like the original and wearing the company mark as a
  lapel pin.
- The pin is composited by `tools/make-team-photo.py` from the vertex list in
  `logo.svg`, not drawn by hand and not prompted, so it cannot drift from the
  mark in the header. Each one is tilted to follow the lapel it sits on.
- Delivered at 900x1125 as a palette PNG - 163 KB. The frame is capped at
  480px tall, so that covers a 2x display; the full-colour composite with its
  alpha channel was 2 MB.

### Fixed

- Re-running the starter content never replaced the team photo. It matched on
  `_aaa_demo_key` alone and returned the existing attachment, so a theme
  update shipping a different photograph would not have reached any site that
  had already imported. The source file's hash is now stored with the
  attachment and the old copy is discarded when it no longer matches. A photo
  the office uploaded itself carries no demo key and is not touched.
- The imported photo says in its Media Library caption that it is a
  placeholder, the way the location photographs carry their credits.

## [1.8.0] - 2026-09-01

### Changed

- **Properties For You shows two full rows**, eight cards rather than four.
- **Twelve demo listings**, up from eight: a Muttrah townhouse, a Muttrah
  Corniche waterfront apartment, an Al Khuwair office floor and an Al Mouj
  marina penthouse.
- Featured listings now top up from the newest when fewer than eight are
  flagged, so the second row is never half empty. An office that has flagged
  nothing still gets a full grid. Featured stops applying once a filter is
  active - someone asking for rentals wants rentals, not the intersection.
- The image strip under the "A Market Built For Investors" cards is gone.

## [1.7.2] - 2026-09-01

### Fixed

- Phones fetched the hero collage tiles they never display. The first tiles
  were marked `eager`, which a browser honours regardless of whether the
  container is `display: none`. Every tile is lazy now; on desktop this
  changes nothing, because an in-viewport lazy image loads immediately anyway.

## [1.7.1] - 2026-09-01

### Changed

- The hero columns drift rather than scroll: 220s, 265s and 310s per loop.

## [1.7.0] - 2026-09-01

### Changed

- **Eighteen photographs in the hero collage**, six per column, every one
  distinct - a repeat inside a column defeats the point, and a repeat across
  columns is visible side by side.
- Tiles are cropped to their own image size rather than reusing the card size,
  and carry a `sizes` hint so the browser stops picking srcset entries far
  larger than the 17vw they render at.
- Square corners, 2px gutters. Spacing is `margin-bottom` rather than `gap`,
  because `gap` makes the two halves of the track unequal and the seam drifts.
- The collage is not rendered on phones at all.

## [1.6.2] - 2026-09-01

### Fixed

- Card meta was still being uppercased in one place after the 1.6.0 sweep.

### Added

- Documentation for the property type and the hero collage settings.

## [1.6.1] - 2026-08-27

### Fixed

- The hero headline broke onto a fourth line. Small caps set far wider than
  the sans they replaced: "On Your Dream Land" needs about 11.4em, so 4.6vw
  wanted 612px in a 511px column.
- Card meta labels read "2 Beds / 2 Baths / 1,200 sq ft" rather than shouting.

## [1.6.0] - 2026-08-27

### Changed

- **Playfair Display SC for headings, Poppins for body.** Neither ships as a
  variable font, so each weight is a separate file - 88 KB for the six.
- **Every `text-transform: uppercase` is gone**, thirteen rules. Playfair
  Display SC is a small-caps face and draws its own small capitals; forcing
  uppercase on top loses them and flattens every word to full height. Labels
  and badges elsewhere now read in normal case to match.
- Display tracking flips from slightly negative to +.02em, which is what a
  serif in small caps wants, and headings sit at 700 - the face has nothing
  heavier.

### Added

- **The hero fills the screen** below the header, measured in `svh` so mobile
  browsers do not size it to the viewport minus their toolbars and leave the
  bottom permanently cropped.
- **Three columns of drifting photographs** own the right half of the hero,
  edge to edge. Positioned and sized in `vw` rather than laid out as a grid
  cell: the container is centred, so its midpoint is the viewport midpoint and
  the text column ends exactly where the collage begins at any width.
- The columns run at unequal, non-harmonic speeds, each starting part-way
  through its own cycle, with the middle one reversed, so they never settle
  into a visible pattern. Each renders its images twice and loops at exactly
  half the track, spaced with margin rather than `gap` so the two halves are
  equal and the seam is invisible.
- Nine Customizer image slots in the same place as before, labelled by the
  column they feed. Under 960px the collage becomes a band beneath the text;
  under 480px it drops to two columns; it holds still under
  `prefers-reduced-motion`.

## [1.5.2] - 2026-08-26

### Fixed

- **1.5.1 took the site down.** A comment added to explain the DOCTYPE pattern
  quoted the pattern itself, and `?>` inside a `//` comment is not commented
  out - PHP closes the tag there and treats the rest of the file as output, so
  the file stopped parsing and every request fataled on requiring it.
- `tools/check-php.py` now detects that, which bracket counting never could.
  It still allows `<?php // note ?>` on one line, where closing the tag is the
  intent, by tracking which line the PHP block opened on.

## [1.5.1] - 2026-08-26

### Fixed

- The sanitiser rejected any SVG carrying a DOCTYPE. Matching to the first `>`
  stops inside the internal subset, leaving a stray `]>` that will not parse -
  and Illustrator writes a DOCTYPE on every export, so ordinary files were
  being turned away as though they were unsafe.

## [1.5.0] - 2026-08-26

### Added

- **SVG uploads, sanitised, for administrators only.** Gated on
  `unfiltered_html`; every file is parsed with DOMDocument and rewritten
  against an element and attribute allowlist. `script`, `foreignObject`,
  `feImage`, `set` and the animate family are refused; every `on*` attribute
  is dropped whether or not it is known; `href` survives only as a
  same-document fragment or a data: image; CSS loses `@import`, `expression()`
  and external `url()`; doctypes and entity declarations are stripped before
  parsing. Anchors are unwrapped rather than deleted, so artwork exported
  inside `<a>` is not lost with the link.
- SVG dimensions are read from the markup, fixing the Customizer logo control:
  WordPress cannot measure a vector with `getimagesize()`, so metadata was
  empty and `wp_get_attachment_image_src()` reported 0x0.
- `wp_check_filetype_and_ext` is reconciled with fileinfo, which reports
  `image/svg` or `text/plain` for SVG - the actual cause of "This file cannot
  be processed by the web server".

## [1.4.1] - 2026-08-26

### Fixed

- The hero wrapped onto a fourth line again: Manrope 800 sets wider than the
  clamp assumed, needing about 570px for the third line in a 484px column.

## [1.4.0] - 2026-08-26

### Added

- **Swappable fonts, no code required.** Customizer > Amir Al Afia >
  Typography points headings and body text at the bundled face, a family
  installed through Appearance > Fonts, or a pasted Google Fonts URL. The Font
  Library installs fonts but cannot restyle a classic theme, so
  `inc/typography.php` translates the choice into the `--display` / `--body`
  custom properties the stylesheet reads. Remote URLs are restricted to a host
  allowlist, and the bundled faces stop being preloaded once a replacement is
  active.

### Changed

- **Manrope replaces Italiana for headings.** Italiana has a single weight, so
  headings had no bold and leaned entirely on size. Manrope is variable
  200-800: the display scale went back up, leading came back in, and headings
  are 700/800 again.
- **Every icon is now Heroicons v2 outline** (MIT) - 24x24, 1.5 stroke, round
  caps, currentColor - taken from the published package rather than
  transcribed. Two exceptions: Heroicons carries no brand marks, so WhatsApp
  and Telegram keep their own glyphs; and it carries no furniture, so bed and
  bath are drawn to the same spec.

## [1.3.1] - 2026-08-26

### Fixed

- Display scale tuned against the real layout: the hero fits three lines again
  (4.4vw pushed it to four), the hero, section titles, statistics and prices
  stopped clipping their caps, the four "Why Oman" headings line up again, and
  body copy on the blue band went from .72 to .84 alpha.

## [1.3.0] - 2026-08-26

### Changed

- **Italiana and Jost replace Barlow Condensed and Barlow.** Italiana has one
  weight, so every display rule sits at 400 and takes its emphasis from size
  and tracking; display sizes came down about a step because Italiana is much
  wider than a condensed sans, and line heights went up because it sits taller
  in its em box.
- **Self-hosted font payload is down from 134 KB to 37 KB.** Google serves Jost
  as one variable font covering 100-900, so the four static weights the site
  requested came back byte-identical; one file with a `font-weight` range
  replaces them.
- **New logo**, whose mark now points up rather than down, across all three
  variants and the favicon.
- **New palette: `#018ED5` primary, `#38B6FF` secondary**, replacing navy and
  cyan. White text on the primary is 3.60:1, so it is a fill and a
  large-display colour only; `--primary-ink` (#016FA6) carries text, and dark
  grounds run `--primary-deep` into `--primary-abyss` to hold white copy above
  7:1. "For Rent" badges moved to the light blue with deep text so they do not
  collapse into "For Sale". `btn-navy` is now `btn-primary`.

## [1.2.4] - 2026-08-25

### Fixed

- JSON-LD carried literal HTML entities. `wp_get_document_title()` returns
  entity-encoded text, which is right for an HTML attribute and wrong inside a
  `<script>` block, where no parser decodes it — every structured-data title
  read "Al Mouj Marina Apartment &#8211; Amir Al AFIA". Decoded on the way in.

## [1.2.3] - 2026-08-25

### Fixed

- **10px of horizontal scroll on phones.** The `.sr-right` reveal translates an
  element 30px right before showing it; on the team photo, which already fills
  the container, that pushed it past the viewport edge. Below 960px the reveal
  is vertical, and the root element gets `overflow-x: clip` as a backstop —
  `clip` rather than `hidden`, because `hidden` on the root makes it a scroll
  container and breaks the sticky header.
- **The navbar wrapped to two lines between 769px and 1180px**, where the logo,
  five links and three labelled buttons stop fitting. In that band the buttons
  keep their icons and drop their labels; tap targets are unchanged.
- The team photo column shrinks below 1100px instead of holding 420px.
- The Oman archive takes its title from the section heading rather than the
  post type's bare label, which is what a shared link was showing.

## [1.2.2] - 2026-08-25

### Fixed

- Place photos imported in 1.1.0 had no 1200x630 share crop, because WordPress
  only generates intermediate sizes at upload time and that size arrived in
  1.2.0. The importer now rebuilds missing sizes for its own attachments,
  skipping originals too small to crop. Media the office uploaded is untouched.

## [1.2.1] - 2026-08-25

### Fixed

- `og:image` fell back to the full image when the source was too small to crop,
  so one page advertised a 1200x558 card. Anything under 600x315 is now
  rejected in favour of the next candidate, and the importer pulls source files
  at 2000px so even a wide panorama clears the 630px the crop needs.
- The front page's `og:title` was the site name alone. A `document_title_parts`
  filter appends the tagline, falling back to a description of the business.

## [1.2.0] — 2026-08-25

### Added

- **A page for every place in the Oman guide.** The Attractions section was six
  tiles that went nowhere. Attractions are now a public post type at `/oman/`,
  each with a researched page: what the place is, how far it is from Muscat,
  when to go, and which residential areas sit near it. Seven places —
  Muttrah Corniche, the traditional houses of Muttrah, the Sultan Qaboos Grand
  Mosque, Wahiba Sands, Wadi Shab, Jebel Shams and Bandar Khayran.
- **Gallery slider on a listing** when more than one photo is set. The track is
  a native scroll-snap container, so swipe and keyboard scrolling work before
  any JavaScript runs; the script adds arrows, thumbnails, a counter and
  arrow-key support. A single photo still renders as a single photo.
- **`screenshot.png`** for Appearance → Themes, and **`share-default.png`**, the
  Open Graph card used when a page has no photo of its own. Both are generated
  by `tools/make-brand-images.py` from the theme's own fonts and logo geometry,
  so they can be regenerated after a palette or wording change.
- Photo credits: the importer records photographer, licence and source URL on
  each attachment, shown on the place pages and in the Media Library.

### Changed

- **Real Omani photography.** The property placeholders were generic villa
  stock that could have been anywhere. They are now photographs of Al Mouj
  Marina, Al Khuwair, Shatti Al Qurum and Muscat's residential coast — real
  Omani architecture, so the site reads as an Omani agency until the office
  uploads its own listing photos.
- **Open Graph rebuilt for chat apps.** A link pasted into WhatsApp or Telegram
  now previews with a large image, title and description. The fix that mattered
  was emitting `og:image:width` and `og:image:height`: without them the
  scrapers will not block on measuring the image and fall back to a thumbnail.
  Also added `og:image:secure_url`, `og:image:type`, `og:image:alt`,
  `twitter:image`, article timestamps, and price/bedroom labels on a listing.
- **SEO structure reworked.** One JSON-LD `@graph` whose nodes cross-reference
  by `@id` instead of repeating the organisation on every page: `WebSite` with
  a `SearchAction`, `RealEstateAgent`, `WebPage`, `BreadcrumbList`, plus
  `RealEstateListing` on a listing, `TouristAttraction` with coordinates on a
  place, and `ItemList` on the properties archive. Added an explicit canonical
  on non-singular views, a robots directive that noindexes search, 404 and
  paged views, and `theme-color`.
- Primary and footer menus point at the real `/properties/` and `/oman/`
  archives rather than page anchors.
- An eighth hero collage image and a 1200×630 `aaa-og` image size.

### Fixed

- Responsive: the listing slider hides its arrows on phones where swipe is the
  gesture; attraction grids reflow at 1100px, 768px and 480px rather than
  staying at six columns; the hero heading uses a viewport-aware clamp instead
  of a fixed 48px; long prices no longer collide with the title on a narrow
  listing header; breadcrumbs wrap.


## [1.1.0] — 2026-08-25

### Fixed

- **Attraction photography showed the wrong places.** The mockup's Unsplash IDs
  did not match their captions: "Historical House" rendered the Great Sphinx,
  "Desert Adventures" rendered Monument Valley, "Stunning Beaches" a city
  skyline, and "Sultan Qaboos Mosque" a man in a suit — two of them not in Oman
  at all. Replaced with six CC0 / Public Domain photographs, each checked to be
  the place it is captioned as: Muttrah Corniche, traditional Muttrah houses,
  the Sultan Qaboos Grand Mosque courtyard, a Wahiba Sands camp, Wadi Shab and
  Jebel Shams.
- **Filtering hid matching listings.** The home-page query preferred properties
  flagged as featured even when a filter was active, so "For Rent" returned one
  rental out of three. Featured now applies only to the unfiltered view.
- **Leads recorded the wrong source page.** The hidden source field used
  `get_the_title()`, which on the front page returns whatever the main query
  left behind — every lead was labelled "Hello world!". It now stores the page
  URL, so an enquiry sent from a listing records that listing.
- Filter terms order by creation rather than alphabetically, so the bar reads
  Apartment / Villas / Luxuries and For Sale / For Rent as designed.
- The hero collage is eight cells, not nine. Cell 4 spans two rows and two
  columns, leaving no room for a ninth; the unused Customizer control is gone.

### Added

- Re-running the starter-content importer repairs an attraction whose photo has
  changed, and prunes demo attachments whose key has left the set.

## [1.0.0] — 2026-08-25

First release: the supplied landing-page mockup rebuilt as a bespoke WordPress
theme with no plugin or third-party theme dependencies.

### Added

- **Property** post type — price, bedrooms, bathrooms, area, address, map link
  and gallery meta, with Type, Deal and Location taxonomies, an archive at
  `/properties/` and a single-listing template.
- **Agent**, **Attraction** and **Lead** post types.
- Listing filters that re-query the database over AJAX, rendered as links to the
  archive so they still filter with JavaScript disabled.
- A working contact form: nonce, honeypot, per-IP rate limit, stored lead and an
  email notification. Posts to `admin-post.php` without JavaScript.
- Customizer panel covering contact details, every section heading, the hero
  stats, the "Why Oman" cards, the hero collage and the team photo.
- Meta description, Open Graph and JSON-LD (`RealEstateAgent` on the home page,
  `Residence` on a listing), suppressed automatically if an SEO plugin appears.
- **Appearance → Starter Content**: a one-click importer that downloads the demo
  photography into the Media Library and creates the demo content.
- `tools/build-theme.py` to package the theme and `tools/check-php.py` to
  balance-check the PHP, since the build machine has no PHP runtime.

### Changed from the mockup

- Barlow and Barlow Condensed are self-hosted (latin subset, six faces) with the
  two above-the-fold faces preloaded, replacing the Google Fonts request.
- The 1.75-second page loader is gone. Scroll reveal is opt-in via a class the
  script adds, so content is never hidden when JavaScript fails.
- Block-library CSS, the emoji script and generator tags are dropped on the
  front end.
- Team WhatsApp and Telegram controls are links rather than dead `<button>`s,
  and property cards link to their listing.

See [docs/DESIGN-REVIEW.md](docs/DESIGN-REVIEW.md) for the full list.

[Unreleased]: https://github.com/moghadam-pro/amiralafia.com/compare/v1.6.1...HEAD
[1.6.1]: https://github.com/moghadam-pro/amiralafia.com/releases/tag/v1.6.1
[1.6.0]: https://github.com/moghadam-pro/amiralafia.com/releases/tag/v1.6.0
[1.5.2]: https://github.com/moghadam-pro/amiralafia.com/releases/tag/v1.5.2
[1.5.1]: https://github.com/moghadam-pro/amiralafia.com/releases/tag/v1.5.1
[1.5.0]: https://github.com/moghadam-pro/amiralafia.com/releases/tag/v1.5.0
[1.4.1]: https://github.com/moghadam-pro/amiralafia.com/releases/tag/v1.4.1
[1.4.0]: https://github.com/moghadam-pro/amiralafia.com/releases/tag/v1.4.0
[1.3.3]: https://github.com/moghadam-pro/amiralafia.com/releases/tag/v1.3.3
[1.3.2]: https://github.com/moghadam-pro/amiralafia.com/releases/tag/v1.3.2
[1.3.1]: https://github.com/moghadam-pro/amiralafia.com/releases/tag/v1.3.1
[1.3.0]: https://github.com/moghadam-pro/amiralafia.com/releases/tag/v1.3.0
[1.2.4]: https://github.com/moghadam-pro/amiralafia.com/releases/tag/v1.2.4
[1.2.3]: https://github.com/moghadam-pro/amiralafia.com/releases/tag/v1.2.3
[1.2.2]: https://github.com/moghadam-pro/amiralafia.com/releases/tag/v1.2.2
[1.2.1]: https://github.com/moghadam-pro/amiralafia.com/releases/tag/v1.2.1
[1.2.0]: https://github.com/moghadam-pro/amiralafia.com/releases/tag/v1.2.0
[1.1.0]: https://github.com/moghadam-pro/amiralafia.com/releases/tag/v1.1.0
[1.0.0]: https://github.com/moghadam-pro/amiralafia.com/releases/tag/v1.0.0
