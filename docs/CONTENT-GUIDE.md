# Content guide

For whoever runs the site day to day. No code involved — everything here is
done from the WordPress dashboard at <https://amiralafia.com/wp-admin>.

---

## Adding a property

**Properties → Add Property.**

1. **Title** — how the listing appears on the card, e.g. *Al Mouj Marina
   Apartment*.
2. **Featured image** (right-hand column) — the photo on the card. Landscape
   works best; it is cropped to 16:9.
3. **Description** — the main text on the listing page.
4. **Excerpt** — one or two sentences. Used in search results and as the page's
   meta description. If you leave it empty WordPress takes the opening of the
   description.
5. **Property Details** (below the editor):
   - **Price** — numbers only, no `$` and no commas. Leave it empty to show
     *Price on request*.
   - **Bedrooms** — a number, or a word like `Studio`.
   - **Bathrooms**, **Area (sqft)** — numbers.
   - **Address line** — shows under the title, e.g. *Al Mouj, Muscat*.
   - **Google Maps link** — optional; makes the address clickable.
   - **Feature on the home page** — see below.
   - **Gallery** — extra photos for the listing page. The featured image is
     always shown first.
6. **Types** and **Deal** (right-hand column) — tick one of each. **Deal** is
   what prints the For Sale / For Rent badge, so it must be set.
7. **Publish.**

### What the home page shows

The home page shows four listings. It shows the ones ticked **Feature on the
home page**; if none are ticked it falls back to the four most recent.

When a visitor uses the filter buttons, featured stops mattering — they see
whatever actually matches. So a rental will always be findable under *For Rent*
even if it is not featured.

### Prices

Prices print with the currency symbol set in **Customizer → Amir Al Afia →
Contact details → Price currency symbol** (currently `$`). Change it there once
and every listing follows.

For rentals, put the annual rent in the price field. It will read the same as a
sale price, so the *For Rent* badge is what tells visitors which it is — always
set the Deal.

---

## The team

**Agents → Add New.** Title is the person's name. Add a **Featured image** if
you want one. Then in **Agent Contact**:

- **Badge** — the small label above the name, e.g. *Seller*, *Support*.
- **Job title** — e.g. *Sales Consultant*.
- **Phone number** — shown large and click-to-call.
- **WhatsApp number** — leave empty to use the phone number.
- **Telegram username** — without the `@`.

The home page shows up to four, ordered by the **Order** field under Page
Attributes (lower numbers first).

## The Oman tiles

**Attractions → Add New.** Title is the vertical label, featured image is the
photo. **Attraction Link** is optional — leave it empty and the tile is not
clickable. Order is the Page Attributes **Order** field. Six are shown.

## Reading enquiries

**Leads.** A count appears next to the menu item when new ones arrive, and
clears when you open the screen.

Each row shows the name, the phone number as a click-to-call link, a direct
**WhatsApp** link, and which page the enquiry was sent from — so an enquiry from
a listing tells you which listing.

Leads are also emailed. Set the address in **Customizer → Amir Al Afia →
Contact details → Send new leads to**. If it is empty, they go to the site
admin address.

Leads cannot be created by hand — they only ever arrive through the form.

## Changing text on the home page

**Appearance → Customize → Amir Al Afia.**

| Section | What is in it |
| --- | --- |
| **Contact details** | Phone, WhatsApp, Telegram, public email, lead notification address, city label, currency symbol. Changing the phone here updates the header, the closing band and the schema markup at once. |
| **Hero** | The three headline lines, the intro paragraph, and the three statistics. |
| **Section headings** | Every badge, heading and intro across the page, the four "Why Oman" cards, the number of property cards, and the form's success message. |
| **Images** | The team photo and the eight hero collage photos. |

The menu is at **Appearance → Menus** — assign one to *Primary menu* and
*Footer menu* to take over from the built-in defaults.

## The starter content

**Appearance → Starter Content** downloads the demo photography and creates the
demo properties, agents and attractions. It is meant to be run once on a fresh
install. Running it again is safe: it reuses what already exists.

**The demo property photos are stock placeholders.** Replace them with real
listing photography before launch. The six Oman photographs are public-domain
images of the actual places and can stay.

## Before launch

- [ ] Replace the demo property photos and text with real listings.
- [ ] Replace the team photo and confirm the agents' names and numbers.
- [ ] Confirm the "Why Oman" claims — the tax, residency and growth figures came
      from the mockup and have not been verified.
- [ ] Confirm the hero statistics (+200 properties, +12 years, 98%).
- [ ] Set the lead notification email and send a test through the form.
- [ ] Set **Settings → General → Site Title and Tagline** — the tagline is used
      as a fallback meta description.
- [ ] Delete the default *Hello world!* post and *Sample Page*.
