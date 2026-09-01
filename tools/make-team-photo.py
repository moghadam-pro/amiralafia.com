#!/usr/bin/env python3
"""Build assets/img/team-photo.png — the "Meet The Team" cut-out.

The mockup shipped with a photograph lifted from someone else's website, which
we cannot use. This replaces it with a commissioned studio shot of two agents,
supplied as a background-removed RGBA cut-out, cropped here to the 4:5 box the
layout reserves and finished with a brand lapel pin on each figure.

The pin is composited rather than photographed or prompted, for the same reason
the share card is drawn rather than exported: it is built from the exact vertex
list in assets/img/logo.svg, so the mark on the lapel cannot drift from the mark
in the header.

Usage:
    python tools/make-team-photo.py <cutout.png>

The cut-out is not kept in the repository (it is a 5 MB intermediate); rerun
this only when the photograph itself is replaced, and pass the new cut-out.

Requires: Pillow.
"""

from __future__ import annotations

import sys
from pathlib import Path

from PIL import Image, ImageDraw, ImageFilter

ROOT = Path(__file__).resolve().parent.parent
OUT = ROOT / "theme" / "amir-al-afia" / "assets" / "img" / "team-photo.png"

PRIMARY = (1, 142, 213)      # #018ED5
SECONDARY = (56, 182, 255)   # #38B6FF
WHITE = (255, 255, 255)

# 4:5 in both cases, because .team-photo reserves that aspect ratio and so the
# browser crops nothing. SIZE is the working canvas; DELIVER is what ships.
SIZE = (1200, 1500)
DELIVER = (900, 1125)

# Where the subjects sit in the supplied cut-out, as a 4:5 window around the
# alpha bounding box with a little air on three sides. The photograph is framed
# at mid-thigh, so the bottom edge is the frame edge.
CROP = (498, 20, 1916, 1792)

# Lapel pins: centre, width, and the tilt that follows each lapel's own angle.
# A pin sitting bolt upright on a slanted lapel is the detail that gives a
# composite away.
PINS = (
    {"center": (192, 622), "size": 54, "angle": -14},  # left figure
    {"center": (678, 545), "size": 56, "angle": -10},  # right figure
)

# Vertex for vertex from assets/img/logo.svg, in its own 63x64 space. The apex
# is listed first and closes the polygon, so it moves to the end when filling.
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


def build_pin(size: int, angle: int) -> Image.Image:
    """One enamel lapel pin: white rounded square carrying the brand mark.

    Drawn at 6x and downsampled — Pillow fills polygons without antialiasing,
    and the chevrons are all long diagonals.
    """
    s = 6
    w = size * s
    pad = int(w * 0.16)  # breathing room inside the pin for the mark

    pin = Image.new("RGBA", (w, w), (0, 0, 0, 0))
    draw = ImageDraw.Draw(pin)
    draw.rounded_rectangle([0, 0, w - 1, w - 1], radius=int(w * 0.17), fill=WHITE + (255,))

    mark = w - pad * 2
    scale = mark / 64.0
    for points, color in ((MARK_BACK, PRIMARY), (MARK_FRONT, SECONDARY)):
        ordered = points[1:] + [points[0]]
        draw.polygon(
            [(pad + px * scale, pad + py * scale) for px, py in ordered],
            fill=color + (255,),
        )

    # Rotate before downsampling: rotating the small copy would re-alias the
    # rounded corners the 6x draw was there to smooth in the first place.
    pin = pin.rotate(angle, Image.BICUBIC, expand=True)
    return pin.resize((pin.width // s, pin.height // s), Image.LANCZOS)


def paste_pin(canvas: Image.Image, pin: Image.Image, center: tuple[int, int]) -> None:
    """Drop a pin onto the jacket, with the shadow that sells it as an object."""
    x = center[0] - pin.width // 2
    y = center[1] - pin.height // 2

    # The pin's own silhouette, offset down-right, dimmed and blurred.
    alpha = Image.new("L", canvas.size, 0)
    alpha.paste(pin.getchannel("A"), (x + 3, y + 4))
    alpha = alpha.point(lambda a: int(a * 0.55)).filter(ImageFilter.GaussianBlur(4))

    shadow = Image.new("RGBA", canvas.size, (0, 20, 34, 0))
    shadow.putalpha(alpha)

    canvas.alpha_composite(shadow)
    canvas.alpha_composite(pin, (x, y))


def main() -> int:
    if len(sys.argv) != 2:
        print("usage: make-team-photo.py <cutout.png>", file=sys.stderr)
        return 2

    source = Image.open(sys.argv[1]).convert("RGBA")
    photo = source.crop(CROP).resize(SIZE, Image.LANCZOS)

    for pin in PINS:
        paste_pin(photo, build_pin(pin["size"], pin["angle"]), pin["center"])

    # Composited at 1200x1500 so the pins have room to be drawn accurately, then
    # brought down to what the layout can actually use: the frame is capped at
    # 480px tall, so 1125 covers a 2x display with a little to spare. Palette
    # PNG because a full-colour photograph with an alpha channel is otherwise a
    # 2 MB file, and this one ships inside the theme zip.
    photo = photo.resize(DELIVER, Image.LANCZOS).quantize(
        colors=256, method=Image.FASTOCTREE, dither=Image.FLOYDSTEINBERG
    )

    photo.save(OUT, "PNG", optimize=True)
    print(f"{OUT.relative_to(ROOT)}  {photo.size[0]}x{photo.size[1]}  {OUT.stat().st_size / 1024:.0f} KB")
    return 0


if __name__ == "__main__":
    sys.exit(main())
