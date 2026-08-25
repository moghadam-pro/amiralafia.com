#!/usr/bin/env python3
"""Package theme/amir-al-afia into dist/amir-al-afia.zip.

The archive contains a single `amir-al-afia/` directory, which is the shape the
WordPress theme installer expects at Appearance > Themes > Add New > Upload.

Python rather than `zip` so the same command works on Windows, macOS and Linux
without extra tooling.
"""

from __future__ import annotations

import re
import subprocess
import sys
import zipfile
from pathlib import Path

ROOT = Path(__file__).resolve().parent.parent
SLUG = "amir-al-afia"
SRC = ROOT / "theme" / SLUG
OUT = ROOT / "dist" / f"{SLUG}.zip"

SKIP_NAMES = {".DS_Store", "Thumbs.db", "desktop.ini"}
SKIP_DIRS = {".git", "node_modules", "__pycache__"}


def theme_version() -> str:
    header = (SRC / "style.css").read_text(encoding="utf-8")
    match = re.search(r"^Version:\s*(.+)$", header, re.M)
    return match.group(1).strip() if match else "unknown"


def main() -> int:
    if not SRC.is_dir():
        print(f"missing {SRC}", file=sys.stderr)
        return 1

    # Refuse to package PHP that cannot possibly parse.
    check = subprocess.run(
        [sys.executable, str(ROOT / "tools" / "check-php.py"), str(ROOT / "theme")],
        check=False,
    )
    if check.returncode != 0:
        return check.returncode

    OUT.parent.mkdir(parents=True, exist_ok=True)
    OUT.unlink(missing_ok=True)

    count = 0
    with zipfile.ZipFile(OUT, "w", zipfile.ZIP_DEFLATED, compresslevel=9) as archive:
        for path in sorted(SRC.rglob("*")):
            if path.is_dir():
                continue
            if path.name in SKIP_NAMES:
                continue
            if SKIP_DIRS & set(path.parts):
                continue
            archive.write(path, (Path(SLUG) / path.relative_to(SRC)).as_posix())
            count += 1

    size_kb = OUT.stat().st_size / 1024
    print(f"built {OUT.relative_to(ROOT)}  v{theme_version()}  {count} files  {size_kb:.0f} KB")
    return 0


if __name__ == "__main__":
    raise SystemExit(main())
