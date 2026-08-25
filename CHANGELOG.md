# Changelog

All notable changes to the Amir Al Afia theme. The format follows
[Keep a Changelog](https://keepachangelog.com/en/1.1.0/), and the project uses
[Semantic Versioning](https://semver.org/spec/v2.0.0.html).

The version here is the same one in `theme/amir-al-afia/style.css` and
[VERSION](VERSION). WordPress compares that header when a theme zip is
re-uploaded, so it must be bumped on every release or the installer reports the
upload as identical to what is already there.

## [Unreleased]

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

[Unreleased]: https://github.com/moghadam-pro/amiralafia.com/compare/v1.2.0...HEAD
[1.2.0]: https://github.com/moghadam-pro/amiralafia.com/releases/tag/v1.2.0
[1.1.0]: https://github.com/moghadam-pro/amiralafia.com/releases/tag/v1.1.0
[1.0.0]: https://github.com/moghadam-pro/amiralafia.com/releases/tag/v1.0.0
