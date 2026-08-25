# Design system

Everything here is defined as custom properties at the top of
`theme/amir-al-afia/assets/css/main.css`. Change a token there and it applies
across the site.

## Colour

| Token | Value | Used for |
| --- | --- | --- |
| `--navy` | `#0B2461` | Primary. Buttons, badges, the investors band, headings on dark. |
| `--navy-dark` | `#071A47` | The far end of the investors gradient. |
| `--cyan` | `#00CDDE` | Brand accent. Large decorative use only — see the note below. |
| `--cyan-ink` | `#0097A4` | Cyan where it has to carry text: the hero headline lines, the For Rent badge. |
| `--blue-mid` | `#1565C0` | Button hover, gradient midpoint. |
| `--text` | `#111827` | Body headings and card text. |
| `--text-muted` | `#5A6473` | Secondary copy. |
| `--text-light` | `#6B7280` | The footer copyright only. |
| `--bg-light` | `#F4F7FB` | Section backgrounds, team cards, the aside on a listing. |
| `--border` | `#E5E9F0` | Every hairline. |
| `--green` | `#1EA952` | WhatsApp. |
| `--telegram` | `#1B8FCC` | Telegram. |
| `--danger` | `#B42318` | Form errors. |

**On the two cyans.** The brand cyan `#00CDDE` is 1.9:1 against white — far
below the 4.5:1 that body text needs and below the 3:1 that even large text
needs. The mockup used it for the hero headline. `--cyan-ink` is the same hue
darkened to 4.6:1, and it is what carries text; `--cyan` stays for fills,
icons on dark, and the hover state of the attraction arrow, where contrast is
not load-bearing.

The WhatsApp and Telegram brand colours were darkened for the same reason —
white text on the official `#25D366` is 2.2:1.

## Type

Two families, self-hosted, latin subset:

- **Barlow Condensed** — 800 and 900. Every heading, the prices, the statistics,
  the agent phone numbers. Always uppercase.
- **Barlow** — 400, 500, 600, 700. Everything else.

| Role | Size | Weight |
| --- | --- | --- |
| Hero headline | `clamp(46px, 5vw, 72px)` | 900 |
| Section title | `clamp(28px, 3vw, 42px)` | 900 |
| Closing band | `clamp(30px, 4vw, 50px)` | 900 |
| Statistic / listing price | 42px / 26px | 900 |
| Card title | 14px | 600 |
| Body | 15px, line-height 1.65 | 400 |
| Section intro | 14.5px, line-height 1.68 | 400 |
| Badge / eyebrow | 10–11px, uppercase, 1px tracking | 700 |

Headline leading is deliberately tight (`0.96`) — Barlow Condensed at display
size needs it.

## Space and shape

Radii: `6px` small controls · `10px` buttons and inputs · `16px` cards ·
`22px` the collage and the listing hero.

Sections are `72px` top and bottom, dropping to `52px` under 768px. The page
shell is `1320px` with a `40px` gutter, `20px` on mobile. Card grids use a
`20px` gap, the collage `6px`, the attraction strip `10px`.

Three shadows, used sparingly: `--shadow-sm` on the sticky header,
`--shadow-md` on hover, `--shadow-lg` on a raised card.

## Motion

One duration for interface transitions — `--transition: .22s ease`. Image zooms
are slower (`.45s`–`.5s`) because they move further.

Scroll reveal is a 26px rise over 600ms, staggered by the `data-delay`
attribute on each element. It is opt-in: the rules only apply once `main.js`
adds `sr-armed` to the root, and they are neutralised under
`prefers-reduced-motion`, along with smooth scrolling and every other
transition.

## Breakpoints

| Width | What changes |
| --- | --- |
| `1100px` | Card and investor grids go to two columns; attractions to three; the image strip and the sticky aside are dropped. |
| `960px` | The hero stacks; the collage drops to four visible photos; the team photo moves above its cards. |
| `768px` | Desktop nav is replaced by the hamburger; grids go single column; gutters halve. |
| `480px` | The form stacks; the collage becomes one photo; team cards stack. |

## Components

**Buttons** — `.btn` plus one of `.btn-navy` (primary), `.btn-outline`
(secondary), `.btn-white` (on dark), `.btn-wa` (WhatsApp). All lift 1px on
hover.

**Filter pill** — `.filter-btn`, an outline pill. Hover changes the border and
text to navy; the selected state, `.is-active`, is filled navy. These are
deliberately different, so hover never reads as selected.

**Property card** — `.prop-card` wrapping a full-card link. 16:9 image with a
bottom gradient, a corner badge, then price, title, location and a metadata row
divided by a hairline. Lifts 5px on hover and shows the shadow on focus-within,
so keyboard users get the same signal.

**Section header** — `.section-tag` eyebrow, `.section-title`, `.section-sub`
capped at 520px so intros stay readable.

## Icons

Inline SVG from `inc/icons.php`, called as `aaa_icon( 'name', size, colour )`.
Colour defaults to `currentColor` so an icon follows its text. They are inlined
rather than sprited because the page uses fewer than twenty and inlining avoids
both a request and a fragment-id dependency.

Available: `phone` `whatsapp` `telegram` `email` `home` `bed` `bath` `area`
`send` `arrow-ne` `arrow-r` `tax` `passport` `calendar` `growth` `pin` `dot`.
