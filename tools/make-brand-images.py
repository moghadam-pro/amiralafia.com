#!/usr/bin/env python3
"""Generate the theme's two brand images.

  assets/img/share-default.png  1200x630  the Open Graph card used when a page
                                          has no photo of its own
  screenshot.png                1200x900  the theme card WordPress shows under
                                          Appearance > Themes

Both are drawn here rather than exported by hand so they can be regenerated
after a palette or wording change, and so they stay byte-identical between
builds. The theme's own woff2 faces are converted to TTF in memory, which
keeps a single source of truth for the typography.

Requires: Pillow, fonttools, brotli.
"""

from __future__ import annotations

import io
import sys
from pathlib import Path

from fontTools.ttLib import TTFont
from PIL import Image, ImageDraw, ImageFilter, ImageFont

ROOT = Path(__file__).resolve().parent.parent
THEME = ROOT / "theme" / "amir-al-afia"
FONTS = THEME / "assets" / "fonts"
IMG = THEME / "assets" / "img"

PRIMARY = (1, 142, 213)        # #018ED5
PRIMARY_INK = (1, 111, 166)    # #016FA6
PRIMARY_DEEP = (1, 82, 124)    # #01527C
ABYSS = (0, 37, 55)            # #002537
SECONDARY = (56, 182, 255)     # #38B6FF
WHITE = (255, 255, 255)


def load_font(slug: str, size: int) -> ImageFont.FreeTypeFont:
    """Load one of the theme's woff2 faces at a given size."""
    face = TTFont(str(FONTS / f"{slug}.woff2"))
    face.flavor = None
    buffer = io.BytesIO()
    face.save(buffer)
    buffer.seek(0)
    return ImageFont.truetype(buffer, size)


def brand_gradient(width: int, height: int) -> Image.Image:
    """The 135-degree navy gradient used by the investors band, plus glow."""
    base = Image.new("RGB", (width, height), PRIMARY_DEEP)
    draw = ImageDraw.Draw(base)

    span = width + height
    for i in range(span):
        t = i / span
        if t < 0.55:
            k = t / 0.55
            color = tuple(round(PRIMARY_DEEP[c] + (PRIMARY_INK[c] - PRIMARY_DEEP[c]) * k) for c in range(3))
        else:
            k = (t - 0.55) / 0.45
            color = tuple(round(PRIMARY_INK[c] + (ABYSS[c] - PRIMARY_INK[c]) * k) for c in range(3))
        draw.line([(i, 0), (0, i)], fill=color)

    # Two soft radial glows, matching the CSS on .investors-section.
    glow = Image.new("RGB", (width, height), (0, 0, 0))
    gdraw = ImageDraw.Draw(glow)
    r1 = int(width * 0.42)
    gdraw.ellipse(
        [int(width * 0.12) - r1, int(height * 0.70) - r1, int(width * 0.12) + r1, int(height * 0.70) + r1],
        fill=(2, 60, 92),
    )
    r2 = int(width * 0.38)
    gdraw.ellipse(
        [int(width * 0.88) - r2, int(height * 0.12) - r2, int(width * 0.88) + r2, int(height * 0.12) + r2],
        fill=(6, 74, 112),
    )
    glow = glow.filter(ImageFilter.GaussianBlur(width // 6))

    return Image.blend(base, Image.blend(base, glow, 0.55), 0.75)


# The two chevrons of the brand mark, taken vertex for vertex from
# assets/img/logo.svg so the drawn version cannot drift from the real one.
# Coordinates are in the SVG's own 63x64 space. The apex is the first vertex
# and closes the polygon, so it is moved to the end when filling.
MARK_BACK = [
    (38.1733, 13.1658), (61.5430, 50.1444), (44.0133, 44.1981), (47.2384, 41.8784),
    (38.8476, 27.8905), (37.9525, 27.8905), (29.3755, 41.1235), (32.8266, 43.7149),
    (15.5110, 50.4574),
]
MARK_FRONT = [
    (24.8400, 13.1658), (48.2097, 50.1444), (30.6800, 44.1981), (33.9051, 41.8784),
    (25.5142, 27.8905), (24.6191, 27.8905), (16.0422, 41.1235), (19.4932, 43.7149),
    (2.17766, 50.4574),
]


def draw_mark(canvas: Image.Image, x: int, y: int, size: int) -> None:
    """The two-chevron brand mark.

    Drawn at 4x and downsampled, because Pillow's polygon fill has no
    antialiasing and the chevrons have long diagonal edges.
    """
    scale = 4
    s = (size / 64.0) * scale
    layer = Image.new("RGBA", (size * scale, size * scale), (0, 0, 0, 0))
    draw = ImageDraw.Draw(layer)

    for points, color in ((MARK_BACK, PRIMARY), (MARK_FRONT, SECONDARY)):
        ordered = points[1:] + [points[0]]
        draw.polygon([(px * s, py * s) for px, py in ordered], fill=color + (255,))

    canvas.paste(
        layer.resize((size, size), Image.LANCZOS),
        (x, y),
        layer.resize((size, size), Image.LANCZOS),
    )


def text_width(draw: ImageDraw.ImageDraw, text: str, font: ImageFont.FreeTypeFont) -> int:
    box = draw.textbbox((0, 0), text, font=font)
    return box[2] - box[0]


def build_share_card() -> Path:
    """The 1200x630 Open Graph card."""
    w, h = 1200, 630
    card = brand_gradient(w, h)
    draw = ImageDraw.Draw(card)

    f_brand = load_font("italiana-400", 42)
    f_head = load_font("italiana-400", 72)
    f_body = load_font("jost-variable", 25)
    f_foot = load_font("jost-variable", 21)
    f_site = load_font("jost-variable", 21)

    pad = 84

    draw_mark(card, pad, 52, 72)
    draw.text((pad + 96, 70), "AMIR AL AFIA", font=f_brand, fill=WHITE)

    y = 190
    for line, color in (
        ("FIND YOUR", SECONDARY),
        ("DREAM PROPERTY", WHITE),
        ("ON YOUR DREAM LAND", SECONDARY),
    ):
        draw.text((pad, y), line, font=f_head, fill=color)
        y += 76

    draw.text(
        (pad, y + 22),
        "Villas, apartments and investment property\nacross Muscat and Oman's finest locations.",
        font=f_body,
        fill=(214, 224, 240),
        spacing=10,
    )

    # Footer rule and line.
    rule_y = h - 96
    draw.line([(pad, rule_y), (w - pad, rule_y)], fill=(40, 110, 155), width=1)
    draw.text((pad, rule_y + 26), "Muscat, Oman", font=f_foot, fill=(205, 218, 238))

    site = "amiralafia.com"
    draw.text((w - pad - text_width(draw, site, f_site), rule_y + 26), site, font=f_site, fill=SECONDARY)

    out = IMG / "share-default.png"
    card.save(out, "PNG", optimize=True)
    return out


def build_screenshot() -> Path:
    """The 1200x900 theme card for Appearance > Themes."""
    w, h = 1200, 900
    shot = brand_gradient(w, h)
    draw = ImageDraw.Draw(shot)

    f_brand = load_font("italiana-400", 46)
    f_head = load_font("italiana-400", 66)
    f_body = load_font("jost-variable", 25)
    f_tag = load_font("jost-variable", 17)
    f_stat = load_font("italiana-400", 52)
    f_lbl = load_font("jost-variable", 16)

    pad = 90

    draw_mark(shot, pad, 74, 78)
    draw.text((pad + 104, 94), "AMIR AL AFIA", font=f_brand, fill=WHITE)

    # Badge.
    badge = "REAL ESTATE · MUSCAT, OMAN"
    bw = text_width(draw, badge, f_tag)
    draw.rounded_rectangle([pad, 214, pad + bw + 34, 214 + 38], radius=6, fill=(2, 74, 112))
    draw.text((pad + 17, 222), badge, font=f_tag, fill=SECONDARY)

    y = 292
    for line, color in (
        ("FIND YOUR", SECONDARY),
        ("DREAM PROPERTY", WHITE),
        ("ON YOUR DREAM LAND", SECONDARY),
    ):
        draw.text((pad, y), line, font=f_head, fill=color)
        y += 70

    draw.text(
        (pad, y + 26),
        "A bespoke WordPress theme with a Property post type,\nworking listing filters and lead capture — and no plugins.",
        font=f_body,
        fill=(214, 224, 240),
        spacing=10,
    )

    # Stat row, echoing the hero.
    stats = (("+200", "PROPERTIES"), ("+12", "YEARS ACTIVE"), ("98%", "SATISFACTION"))
    x = pad
    base = h - 190
    for i, (value, label) in enumerate(stats):
        draw.text((x, base), value, font=f_stat, fill=WHITE)
        draw.text((x, base + 62), label, font=f_lbl, fill=(150, 190, 220))
        x += max(text_width(draw, value, f_stat), text_width(draw, label, f_lbl)) + 74
        if i < len(stats) - 1:
            draw.line([(x - 40, base + 8), (x - 40, base + 62)], fill=(40, 110, 155), width=1)

    out = THEME / "screenshot.png"
    shot.save(out, "PNG", optimize=True)
    return out


def main() -> int:
    for path in (build_share_card(), build_screenshot()):
        size_kb = path.stat().st_size / 1024
        with Image.open(path) as im:
            print(f"{path.relative_to(ROOT)}  {im.size[0]}x{im.size[1]}  {size_kb:.0f} KB")
    return 0


if __name__ == "__main__":
    sys.exit(main())
