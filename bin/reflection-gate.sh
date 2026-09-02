#!/usr/bin/env bash
#
# Counts hand-rolled type-metadata reflection outside the Metadata component.
#
# Reflection is not the problem and zero reflection is not the goal -- it has to happen somewhere.
# The goal is a single owner behind an interface, inside Metadata. So this gate is scoped to the three
# consuming trees by PATH, never by pattern: reflection added inside Metadata's registry layer later
# can neither trip this check nor silently satisfy it.
#
# Exit codes: 0 clean run (count on stdout), 1 self-check failed, 2 usage error.
#
# Usage:
#   bin/reflection-gate.sh                 count violations (survivors on stderr, count on stdout)
#   bin/reflection-gate.sh --show-exempt   list what the allowlist suppresses, and verify each rule fires
#   bin/reflection-gate.sh --targets a,b   override the scanned trees (used to prove the gate can fail)

set -euo pipefail

ROOT="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
cd "$ROOT"

# `get_declared_classes(` is in here deliberately. The worst offender in this codebase is a hand-rolled
# type registry built on it, and a search for `Reflection` cannot see it. A pattern that misses it would
# report success while a load-order-dependent, profile-blind registry survived intact.
#
# The trailing signature clause enforces the boundary rule: a \ReflectionProperty in a method SIGNATURE
# is a violation even when the body never instantiates one, because the handle still crosses a package.
PATTERN='new \\?Reflection|::getAttributes\(|->getAttributes\(|get_declared_classes\(|\\?Reflection(Class|Property|Method|Parameter|Attribute|Enum|NamedType|UnionType)[ &|]'

DEFAULT_TARGETS="src/Component/Serialization/src src/Component/Validation/src src/Component/FHIRPath/src"
CONTROL_DIR="src/Component/Metadata/src"
ALLOWLIST="bin/reflection-exemptions.txt"

MODE="count"
TARGETS="$DEFAULT_TARGETS"
while [ $# -gt 0 ]; do
  case "$1" in
    --show-exempt) MODE="exempt"; shift ;;
    --targets)     TARGETS="$(printf '%s' "${2:-}" | tr ',' ' ')"; shift 2 ;;
    -h|--help)     sed -n '2,20p' "$0"; exit 0 ;;
    *) echo "gate: unknown argument '$1'" >&2; exit 2 ;;
  esac
done

fail() { echo "gate: SELF-CHECK FAILED -- $1" >&2; exit 1; }

# A gate that examined nothing must not be able to report success.
for d in $TARGETS; do
  [ -d "$d" ] || fail "target tree '$d' does not exist, so a count of 0 would be meaningless"
done
[ -d "$CONTROL_DIR" ] || fail "control tree '$CONTROL_DIR' does not exist"

# Positive control: Metadata legitimately reflects, so this must stay non-zero. If it ever reads 0 the
# pattern itself has broken and every other number this script prints is worthless.
CONTROL_COUNT="$(grep -rnE "$PATTERN" "$CONTROL_DIR" --include='*.php' | wc -l | tr -d ' ')"
[ "$CONTROL_COUNT" -gt 0 ] || fail "positive control over $CONTROL_DIR returned 0; the pattern no longer matches known reflection"

RAW="$(grep -rnE "$PATTERN" $TARGETS --include='*.php' || true)"

# The allowlist holds ONLY per-instance state probes -- "has this typed slot been assigned" -- which no
# per-class registry can answer. Each rule is `path::substring`, matched against the file and the code
# rather than a line number, so an unrelated edit above cannot silently widen an exemption.
# Any line ADDED to that file is a review event, not a pass.
run_filter() {
  awk -v allow="$ALLOWLIST" -v mode="$1" '
    BEGIN {
      n = 0
      if (allow != "" && (getline line < allow) >= 0) {
        do {
          if (line ~ /^[[:space:]]*#/ || line ~ /^[[:space:]]*$/) continue
          split(line, kv, "::")
          n++; rp[n] = kv[1]; rs[n] = kv[2]; hit[n] = 0
        } while ((getline line < allow) > 0)
      }
    }
    {
      split($0, f, ":"); path = f[1]
      body = $0; sub(/^[^:]*:[0-9]+:/, "", body)
      exempt = 0
      for (i = 1; i <= n; i++) {
        if (path == rp[i] && index(body, rs[i]) > 0) { exempt = 1; hit[i] = 1 }
      }
      if (mode == "exempt" && exempt) print
      if (mode == "count"  && !exempt) print
    }
    END {
      if (mode == "exempt") {
        for (i = 1; i <= n; i++)
          if (!hit[i]) printf("gate: STALE RULE never matched -> %s::%s\n", rp[i], rs[i]) > "/dev/stderr"
      }
    }
  '
}

# A missing allowlist is a hard failure, not a warning. Continuing without it would quietly report a
# different number for the same tree, which is the exact false-confidence this gate exists to prevent.
# The allowlist lives beside this script rather than under .goat-flow/, whose .gitignore is default-deny
# and would have kept it out of the repository entirely.
[ -f "$ALLOWLIST" ] || fail "allowlist '$ALLOWLIST' is missing; counts would not be comparable to the recorded baseline"

SURVIVORS="$(printf '%s\n' "$RAW" | grep -v '^$' | run_filter count || true)"
EXEMPTED="$(printf '%s\n' "$RAW" | grep -v '^$' | run_filter exempt || true)"

if [ "$MODE" = "exempt" ]; then
  printf '%s\n' "$EXEMPTED" | grep -v '^$' || true
  printf '%s\n' "$EXEMPTED" | grep -cv '^$' || true
  exit 0
fi

# Survivors go to stderr so a non-zero result names its own offenders; only the count reaches stdout.
printf '%s\n' "$SURVIVORS" | grep -v '^$' >&2 || true
echo "gate: positive control $CONTROL_DIR = $CONTROL_COUNT (must be > 0)" >&2
printf '%s\n' "$SURVIVORS" | grep -cv '^$' || true
