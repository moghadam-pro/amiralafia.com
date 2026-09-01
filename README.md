# amiralafia.com

Website for **Amir Al Afia Real Estate** — a buying, selling and rental agency
in Muscat, Oman.

The site runs on WordPress with a single bespoke theme and **no plugins**. The
Property listings, the team, the contact form and the SEO tags are all handled
by the theme itself.

- **Live:** <https://amiralafia.com>
- **Theme version:** see [VERSION](VERSION) · [CHANGELOG.md](CHANGELOG.md)

---

## Repository layout

| Path | What it holds |
| --- | --- |
| `theme/amir-al-afia/` | The WordPress theme — the actual product. |
| `design/` | The original AI-generated mockup, kept verbatim for reference. |
| `docs/` | Architecture, design system, deployment and the office's content guide. |
| `tools/` | `build-theme.py` packages the theme, `check-php.py` lints it, `fetch-fonts.sh` refreshes the self-hosted fonts, `make-brand-images.py` regenerates `screenshot.png` and the share card. |
| `dist/amir-al-afia.zip` | The build artifact uploaded to WordPress. Tracked deliberately — see [docs/DEPLOYMENT.md](docs/DEPLOYMENT.md). |

## Quick start

Build the installable theme zip:

```bash
python tools/build-theme.py
```

That writes `dist/amir-al-afia.zip`, refusing to package PHP whose brackets do
not balance. Upload it at **Appearance → Themes → Add New → Upload Theme**.

On a fresh install, activate the theme and then run **Appearance → Starter
Content** once. It downloads the demo photography into the Media Library and
creates the demo properties, agents and attractions.

## What the theme provides

**Content types**

- **Property** — price, bedrooms, bathrooms, area, address, map link and a
  gallery, plus Type / Deal / Location taxonomies. Has an archive at
  `/properties/` and a single-listing page.
- **Agent** — the people in "Meet The Team", with click-to-call, WhatsApp and
  Telegram links.
- **Attraction** — the "Attractions & Nature of Oman" tiles, each a page of its
  own at `/oman/…`: what the place is, how far it is from Muscat, when to go,
  and which residential areas sit near it.
- **Lead** — every contact-form submission, stored so nothing is lost if an
  email notification bounces.

**Behaviour**

- Listing filters re-query the database over AJAX, and are rendered as links to
  the archive so they still filter with JavaScript disabled.
- The contact form is real: nonce, honeypot, per-IP rate limit, stored lead and
  an email to the office. It posts to `admin-post.php` without JavaScript.
- A listing with more than one photo renders a gallery slider. The track is a
  native scroll-snap container, so swipe and keyboard scrolling work with
  JavaScript blocked; the script adds arrows, thumbnails and a counter.
- Canonical, robots, Open Graph, Twitter cards and a single JSON-LD `@graph`
  are emitted by the theme, and are skipped automatically if an SEO plugin is
  ever installed. Links pasted into WhatsApp or Telegram preview with a
  1200×630 card; pages with no photo of their own fall back to a bundled
  brand card.

**Editing without code** — phone numbers, social handles, every section
heading, the three hero stats, the "Why Oman" cards, the nine hero photos and
the team photo are all in **Customizer → Amir Al Afia**.

## Documentation

- [docs/ARCHITECTURE.md](docs/ARCHITECTURE.md) — how the theme is put together and why.
- [docs/CONTENT-GUIDE.md](docs/CONTENT-GUIDE.md) — for the office: adding a property, an agent, reading leads.
- [docs/DESIGN-SYSTEM.md](docs/DESIGN-SYSTEM.md) — colours, type, spacing, components.
- [docs/DESIGN-REVIEW.md](docs/DESIGN-REVIEW.md) — every defect found in the supplied mockup and what was done about it.
- [docs/DEPLOYMENT.md](docs/DEPLOYMENT.md) — build, upload, release checklist.
- [NOTES.md](NOTES.md) — working notes: conventions, and the traps this codebase has already sprung.

## Requirements

WordPress 6.5+, PHP 8.1+. Currently running WordPress 7.1 on PHP 8.3 (nginx,
CloudPanel).
