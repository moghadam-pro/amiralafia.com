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

## The Oman guide

**Attractions → Add New.** Each of these is a real page at `/oman/…`, not just
a tile on the home page.

- **Title** — also the vertical label on the tile.
- **Featured image** — the tile photo and the page's hero.
- **Excerpt** — the sentence under the heading, and the description used when
  the page is shared.
- **Content** — the body of the page.
- **Attraction Facts**: region, drive time from Muscat, best season, the
  residential areas nearby, latitude and longitude, and an optional external
  link. Anything left empty is simply not shown.

The home page shows six, ordered by the Page Attributes **Order** field. The
index at `/oman/` shows them all.

## Sharing a link

Paste any page's URL into WhatsApp, Telegram, Facebook or LinkedIn and it
previews with a photo, the title and the description. The photo is the page's
featured image; a page without one falls back to a branded card.

If you change a photo or the text and the old version still shows, the chat app
has cached it — that cache clears on its own, usually within a day. Facebook's
Sharing Debugger can force a refresh for Facebook and WhatsApp.

## Changing the fonts

**Appearance -> Customize -> Amir Al Afia -> Typography.** Headings and body
text are set separately, and each has three options.

**Theme default** - Manrope for headings, Jost for body. Both are stored on
this server, so nothing is fetched from Google and nothing breaks if Google is
unreachable. This is the fastest option.

**A font from Appearance -> Fonts** - the better way to use a different font.
Go to **Appearance -> Fonts -> Install Fonts**, search the Google catalogue and
install the family you want; WordPress downloads the files onto this server.
Then come back to Typography and pick it from the dropdown. The option only
appears once at least one font has been installed there.

Installing a font at Appearance -> Fonts does **not** change the site on its
own. That screen adds the font to WordPress; the Typography section is what
tells the theme to use it.

**A Google Fonts link** - the quickest, at a cost. On fonts.google.com, choose
your font and its weights, open the **Get font -> Get embed code** panel, and
copy the address out of the `<link>` it shows you. It looks like this:

```
https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400..900&display=swap
```

Paste that whole address into the field. The theme reads the font name out of
it, so the "font name override" box below can stay empty.

Two things to watch:

- **Include bold weights.** Headings are set at 800, so pick a range that goes
  that high - `wght@400..900`, or tick the bold weights in the panel. A font
  that only ships one weight will look thin and flat, which is exactly what
  went wrong with the first attempt at Italiana.
- **This loads the font from Google on every page view**, which is slower and
  hands your visitors' IP addresses to a third party. If that matters, use
  Appearance -> Fonts instead - same font, served from here.

Only Google Fonts and Bunny Fonts addresses are accepted, so a mistyped or
pasted-from-elsewhere link is ignored rather than loaded.

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
| **Typography** | The heading and body fonts - see above. |

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
