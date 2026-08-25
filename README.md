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
| `tools/` | `build-theme.py` packages the theme, `check-php.py` lints it, `fetch-fonts.sh` refreshes the self-hosted fonts. |
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
- **Attraction** — the "Attractions & Nature of Oman" tiles.
- **Lead** — every contact-form submission, stored so nothing is lost if an
  email notification bounces.

**Behaviour**

- Listing filters re-query the database over AJAX, and are rendered as links to
  the archive so they still filter with JavaScript disabled.
- The contact form is real: nonce, honeypot, per-IP rate limit, stored lead and
  an email to the office. It posts to `admin-post.php` without JavaScript.
- Meta description, Open Graph and JSON-LD are emitted by the theme, and are
  skipped automatically if an SEO plugin is ever installed.

**Editing without code** — phone numbers, social handles, every section
heading, the three hero stats, the "Why Oman" cards, the hero collage and the
team photo are all in **Customizer → Amir Al Afia**.

## Documentation

- [docs/ARCHITECTURE.md](docs/ARCHITECTURE.md) — how the theme is put together and why.
- [docs/CONTENT-GUIDE.md](docs/CONTENT-GUIDE.md) — for the office: adding a property, an agent, reading leads.
- [docs/DESIGN-SYSTEM.md](docs/DESIGN-SYSTEM.md) — colours, type, spacing, components.
- [docs/DESIGN-REVIEW.md](docs/DESIGN-REVIEW.md) — every defect found in the supplied mockup and what was done about it.
- [docs/DEPLOYMENT.md](docs/DEPLOYMENT.md) — build, upload, release checklist.

## Requirements

WordPress 6.5+, PHP 8.1+. Currently running WordPress 7.1 on PHP 8.3 (nginx,
CloudPanel).
