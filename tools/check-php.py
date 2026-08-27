#!/usr/bin/env python3
"""A brace/paren balance check for the theme's PHP files.

This is not a parser and it is no substitute for `php -l`; it exists because
the build machine has no PHP runtime. It tokenises just enough to skip strings,
comments and heredocs, then reports files whose brackets do not balance and
files that end inside a string or comment. Those are the mistakes a hand-edited
template is most likely to carry, and the ones that produce a white screen.
"""

from __future__ import annotations

import re
import sys
from pathlib import Path

PAIRS = {"{": "}", "(": ")", "[": "]"}
CLOSERS = {v: k for k, v in PAIRS.items()}


def check(path: Path) -> list[str]:
    src = path.read_text(encoding="utf-8")
    errors: list[str] = []
    stack: list[tuple[str, int]] = []

    i = 0
    line = 1
    n = len(src)
    in_php = False
    php_opened_on = 0

    while i < n:
        ch = src[i]

        if ch == "\n":
            line += 1
            i += 1
            continue

        if not in_php:
            nxt = src.find("<?php", i)
            if nxt == -1:
                break
            line += src.count("\n", i, nxt)
            i = nxt + 5
            in_php = True
            php_opened_on = line
            continue

        if src.startswith("?>", i):
            in_php = False
            i += 2
            continue

        # Comments. A `?>` inside a one-line comment is NOT commented out: PHP
        # closes the tag there and treats everything after it as page output.
        # It is an easy trap whenever a comment quotes markup or a regex, it
        # takes the whole site down, and no amount of bracket counting sees it.
        #
        # `<?php // note ?>` on a single line is the ordinary template idiom and
        # is fine, because closing the tag is the intent. The dangerous shape is
        # a `?>` that lands mid-comment inside a longer block.
        if src.startswith("//", i) or ch == "#":
            end = src.find("\n", i)
            end = n if end == -1 else end
            comment = src[i:end]
            marker = comment.find("?>")

            if marker != -1:
                inline_idiom = php_opened_on == line and not comment[marker + 2:].strip()
                if not inline_idiom:
                    errors.append(
                        f"{path}:{line}: '?>' inside a one-line comment closes the PHP tag"
                    )
                # Model what PHP actually does: the tag is now closed.
                in_php = False
                i = i + marker + 2
                continue

            i = end
            continue
        if src.startswith("/*", i):
            end = src.find("*/", i + 2)
            if end == -1:
                errors.append(f"{path}:{line}: unterminated /* comment")
                break
            line += src.count("\n", i, end)
            i = end + 2
            continue

        # Heredoc / nowdoc.
        m = re.match(r"<<<\s*(['\"]?)([A-Za-z_]\w*)\1\r?\n", src[i:])
        if m:
            label = m.group(2)
            body_start = i + m.end()
            close = re.search(rf"^\s*{label}\b", src[body_start:], re.M)
            if not close:
                errors.append(f"{path}:{line}: unterminated heredoc {label}")
                break
            end = body_start + close.end()
            line += src.count("\n", i, end)
            i = end
            continue

        # Strings.
        if ch in "'\"":
            quote = ch
            j = i + 1
            while j < n:
                if src[j] == "\\":
                    j += 2
                    continue
                if src[j] == quote:
                    break
                j += 1
            if j >= n:
                errors.append(f"{path}:{line}: unterminated {quote} string")
                break
            line += src.count("\n", i, j)
            i = j + 1
            continue

        if ch in PAIRS:
            stack.append((ch, line))
        elif ch in CLOSERS:
            if not stack:
                errors.append(f"{path}:{line}: stray '{ch}'")
            elif stack[-1][0] != CLOSERS[ch]:
                opener, opened_at = stack[-1]
                errors.append(
                    f"{path}:{line}: '{ch}' closes '{opener}' opened on line {opened_at}"
                )
                stack.pop()
            else:
                stack.pop()

        i += 1

    for opener, opened_at in stack:
        errors.append(f"{path}:{opened_at}: '{opener}' is never closed")

    return errors


def main() -> int:
    root = Path(sys.argv[1] if len(sys.argv) > 1 else "theme")
    failures = 0

    for path in sorted(root.rglob("*.php")):
        errors = check(path)
        if errors:
            failures += 1
            for error in errors:
                print(error)

    total = len(list(root.rglob("*.php")))
    if failures:
        print(f"\n{failures} of {total} PHP files look unbalanced.")
        return 1

    print(f"{total} PHP files balanced.")
    return 0


if __name__ == "__main__":
    raise SystemExit(main())
