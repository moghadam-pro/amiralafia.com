#!/usr/bin/env python3
"""Build assets/img/team-photo.png — the "Meet The Team" cut-out.

The mockup shipped with a photograph lifted from another agency's website,
which we cannot use. This replaces it with a commissioned studio shot of two
consultants, supplied as a background-removed RGBA cut-out and cropped here to
the frame .team-photo reserves.

The crop is deliberately tight — head to just above the hands, the framing the
mockup's photograph used. That is what makes it impossible to also hold the 4:5
the frame used to be: at this height, 4:5 is 1070px wide against a 1396px-wide
pair, so it would take 163px off each of the outer arms. The frame follows the
photograph instead, at 11:10.

Usage:
    python tools/make-team-photo.py <cutout.png>

The cut-out is not kept in the repository (it is a 5 MB intermediate); rerun
this only when the photograph itself is replaced.

Requires: Pillow.
"""

from __future__ import annotations

import sys
from pathlib import Path

from PIL import Image

ROOT = Path(__file__).resolve().parent.parent
OUT = ROOT / "theme" / "amir-al-afia" / "assets" / "img" / "team-photo.png"

# 11:10 in both cases, matching .team-photo. DELIVER is 2x the 420px column the
# frame sits in, which is all it can use.
DELIVER = (880, 800)

# The window on the supplied cut-out: a little air above the heads, the outer
# arms clear of both edges, and the bottom cut across the forearms.
CROP = (464, 40, 1949, 1390)


def main() -> int:
    if len(sys.argv) != 2:
        print("usage: make-team-photo.py <cutout.png>", file=sys.stderr)
        return 2

    photo = Image.open(sys.argv[1]).convert("RGBA").crop(CROP).resize(DELIVER, Image.LANCZOS)

    # Palette PNG: a full-colour photograph with an alpha channel is otherwise
    # well over a megabyte, and this one ships inside the theme zip.
    photo = photo.quantize(colors=256, method=Image.FASTOCTREE, dither=Image.FLOYDSTEINBERG)

    photo.save(OUT, "PNG", optimize=True)
    print(f"{OUT.relative_to(ROOT)}  {photo.size[0]}x{photo.size[1]}  {OUT.stat().st_size / 1024:.0f} KB")
    return 0


if __name__ == "__main__":
    sys.exit(main())
