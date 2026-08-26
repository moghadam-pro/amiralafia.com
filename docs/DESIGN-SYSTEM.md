# Design system

Everything here is defined as custom properties at the top of
`theme/amir-al-afia/assets/css/main.css`. Change a token there and it applies
across the site.

## Colour

The brand is two blues: **#018ED5** primary and **#38B6FF** secondary. Neither
can carry white text, so the palette adds darker steps of the primary for
anything that has to be legible.

| Token | Value | Used for |
| --- | --- | --- |
| `--primary` | `#018ED5` | The brand blue. Fills, icons, the hero headline lines, active states. |
| `--primary-ink` | `#016FA6` | Anything carrying white text, or sitting as text on white: buttons, links, badges, prices. |
| `--primary-deep` | `#01527C` | Dark surfaces and the light end of the dark gradients. |
| `--primary-abyss` | `#002537` | The deep end of gradients, overlays, text on the light blue. |
| `--secondary` | `#38B6FF` | Accent on dark grounds, glows, hover states, "For Rent". |
| `--secondary-deep` | `#0179B5` | The light end of the closing band gradient. |
| `--secondary-wash` | `#E8F6FF` | Pale tint for chips and washes. |
| `--text` | `#111827` | Body headings and card text. |
| `--text-muted` | `#5A6473` | Secondary copy. |
| `--bg-light` | `#F2F8FC` | Section backgrounds, team cards, the aside on a listing. |
| `--border` | `#DCE8F1` | Every hairline. |
| `--green` / `--telegram` | `#1EA952` / `#1B8FCC` | WhatsApp and Telegram, darkened from their brand colours so white labels pass. |

**The contrast rule.** White on `#018ED5` is **3.60:1** — fine for large text
and UI, below AA for anything smaller. So the brand blue is a fill and a
display colour; `--primary-ink` (5.49:1 on white, and white on it) is what
carries text. The hero headline is the deliberate exception: at 40px and up it
counts as large text, where 3:1 is the bar, so it uses the brand blue itself.

Dark grounds run `--primary-deep` into `--primary-abyss`, which keeps white
copy above 7:1 across the whole gradient.

## Type

Two faces, self-hosted, latin subset — 37 KB in total:

- **Italiana** — every heading, price, statistic and large display use. Always
  uppercase. **It has exactly one weight.** There is no bold, so every rule
  that sets it stays at `font-weight: 400`; asking for 700 or 900 gets a
  synthesised smear across strokes that are already fine. Emphasis comes from
  size and tracking instead.
- **Jost** — everything else. Shipped as a single variable file covering
  100–900, so weight is free: 400 body, 500 nav, 600 labels and buttons.

| Role | Size | Face |
| --- | --- | --- |
| Hero headline | `clamp(32px, 3.9vw, 56px)` | Italiana 400 |
| Section title | `clamp(25px, 2.7vw, 38px)` | Italiana 400 |
| Closing band | `clamp(27px, 3.4vw, 44px)` | Italiana 400 |
| Statistic / listing price | 40px / 30px | Italiana 400 |
| Card title | 14px | Jost 600 |
| Body | 15.5px, line-height 1.65 | Jost 400 |
| Badge / eyebrow | 10–11px, uppercase, .09em | Jost 600 |

**Two things Italiana changed.** It is much wider than Barlow Condensed at the
same size, so every display size came down roughly a step — at `4.4vw` the hero
pushed its third line onto a fourth. And it sits taller in its em box, so line
heights that suited a condensed sans clipped the caps: nothing set in Italiana
should use `line-height: 1`.

Tracking is positive everywhere Italiana is set in caps (`.012em`–`.04em`),
which the face needs to stop the letterforms closing up.

## Space and shape

Radii: `6px` small controls · `10px` buttons and inputs · `16px` cards ·
`22px` the collage, the listing hero and the slider.

Sections are `72px` top and bottom, dropping to `52px` under 768px. The page
shell is `1320px` with a `40px` gutter, `20px` on mobile. Card grids use a
`20px` gap, the collage `6px`, the attraction strip `10px`.

Three shadows, tinted with the deep blue rather than neutral black:
`--shadow-sm` on the sticky header, `--shadow-md` on hover, `--shadow-lg` on a
raised card.

## Motion

One duration for interface transitions — `--transition: .22s ease`. Image zooms
are slower (`.45s`–`.5s`) because they move further.

Scroll reveal is a 26px rise over 600ms, staggered by the `data-delay`
attribute on each element. It is opt-in: the rules only apply once `main.js`
adds `sr-armed` to the root, they are neutralised under
`prefers-reduced-motion`, and below 960px the sideways variant becomes vertical
because there is no horizontal slack to translate into.

## Breakpoints

| Width | What changes |
| --- | --- |
| `1180px` | Header CTAs drop their labels and keep their icons, so the nav stays on one line. |
| `1100px` | Card and investor grids go to two columns; attractions to three; the image strip and the sticky aside are dropped; the team photo column starts shrinking. |
| `960px` | The hero stacks; the collage drops to four visible photos; the team photo moves above its cards; sideways reveal becomes vertical. |
| `768px` | Desktop nav is replaced by the hamburger; grids go single column; gutters halve; the slider hides its arrows in favour of swipe. |
| `480px` | The form stacks; the collage becomes one photo; team cards stack. |

## Components

**Buttons** — `.btn` plus one of `.btn-primary`, `.btn-outline`, `.btn-white`
(on dark), `.btn-wa`. All lift 1px on hover.

**Filter pill** — `.filter-btn`, an outline pill. Hover changes the border and
text; the selected state, `.is-active`, is a filled pill. These are
deliberately different, so hover never reads as selected.

**Property card** — `.prop-card` wrapping a full-card link. 16:9 image with a
bottom gradient, a corner badge, then price, title, location and a metadata row
divided by a hairline. "For Sale" is a solid `--primary-ink` badge with white
text; "For Rent" is `--secondary` with deep text, so the two never look alike.

**Gallery slider** — `.sp-slider`, a scroll-snap track. See
`docs/ARCHITECTURE.md` for why the controls only appear once JavaScript runs.

## Logo

`assets/img/logo.svg` is the full lockup; `inc/logo-inline.svg` is the same
markup inlined with an accessible label, and `inc/logo-mark-inline.svg` is the
mark alone. The filter id is namespaced `aaa-logo-shadow`, because several
copies appear on one page and a duplicate id would make every drop shadow
resolve to whichever came first.

The mark's vertices are duplicated in `tools/make-brand-images.py` so the
generated brand images match. If the logo changes, both must change.

## Icons

Inline SVG from `inc/icons.php`, called as `aaa_icon( 'name', size, colour )`.
Colour defaults to `currentColor` so an icon follows its text.

Available: `phone` `whatsapp` `telegram` `email` `home` `bed` `bath` `area`
`send` `arrow-ne` `arrow-r` `tax` `passport` `calendar` `growth` `pin` `dot`.
