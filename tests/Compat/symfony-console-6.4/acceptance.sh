#!/usr/bin/env bash
#
# Acceptance checks for the fhir:generate / fhir:generate-ig commands against the installed
# symfony/console version. Intended for the 6.4 lower bound, but it runs unchanged on 7.x,
# which is what makes the two comparable.
#
# Usage:
#   composer install --working-dir=tests/Compat/symfony-console-6.4
#   tests/Compat/symfony-console-6.4/acceptance.sh [--with-generation]
#
# --with-generation additionally runs a real R4B generation, which needs network access and
# writes into this harness's vendor/ardenexal/Models/ sandbox. Without it the script stays
# offline and only exercises option parsing plus the guarded failure paths.

set -euo pipefail

cd "$(dirname "$0")"

WITH_GENERATION=0
if [[ "${1:-}" == "--with-generation" ]]; then
    WITH_GENERATION=1
elif [[ -n "${1:-}" ]]; then
    echo "unknown argument: $1" >&2
    exit 64
fi

if [[ ! -f vendor/autoload.php ]]; then
    echo "harness is not installed — run: composer install --working-dir=$(pwd)" >&2
    exit 1
fi

failures=0

fail() {
    echo "  FAIL — $1"
    failures=$((failures + 1))
}

pass() {
    echo "  ok   — $1"
}

# Runs the harness and prints its output, without letting a non-zero exit abort the script:
# several checks below assert on deliberate failure paths.
run() {
    php console.php "$@" 2>&1 || true
}

console_version="$(php -r '
    $packages = json_decode(file_get_contents("vendor/composer/installed.json"), true)["packages"];
    foreach ($packages as $package) {
        if ($package["name"] === "symfony/console") {
            echo ltrim($package["version"], "v");
        }
    }
')"

echo "symfony/console ${console_version}"

# Documents why this harness exists: on 6.4 these attribute classes are absent, so the
# invokable-command style silently registers no options at all.
echo
echo "console attribute availability"
php -r '
    require "vendor/autoload.php";
    foreach (["AsCommand", "Option", "Argument", "Ask"] as $name) {
        $class = "Symfony\\Component\\Console\\Attribute\\" . $name;
        printf("  %-10s %s%s", $name, class_exists($class) ? "present" : "absent", PHP_EOL);
    }
'

echo
echo "both commands are registered"
listing="$(run list fhir)"
for command in fhir:generate fhir:generate-ig; do
    if grep -q "  ${command} " <<<"$listing"; then
        pass "${command} is listed"
    else
        fail "${command} is missing from 'list fhir'"
    fi
done

echo
echo "options are declared (the invokable style registered none of these on 6.4)"
for command in fhir:generate fhir:generate-ig; do
    help_output="$(run help "${command}")"
    for option in --package --offline; do
        if grep -q -- "${option}" <<<"$help_output"; then
            pass "${command} ${option}"
        else
            fail "${command} does not declare ${option}"
        fi
    done
    if grep -q "multiple values allowed" <<<"$help_output"; then
        pass "${command} --package is repeatable"
    else
        fail "${command} --package is not declared as an array option"
    fi
done

echo
echo "invoking the commands does not hit the 6.4 failure modes"
for invocation in \
    "fhir:generate-ig --package=hl7.fhir.au.base --offline" \
    "fhir:generate --package=hl7.fhir.r4b.core --offline"; do
    # shellcheck disable=SC2086
    output="$(run ${invocation})"
    if grep -q "You must override the execute() method" <<<"$output"; then
        fail "${invocation} — execute() is not overridden"
    elif grep -q "option does not exist" <<<"$output"; then
        fail "${invocation} — an option was not registered"
    else
        pass "${invocation}"
    fi
done

echo
echo "--package repetition preserves order"
order_output="$(run fhir:generate-ig --package=hl7.fhir.au.base --package=hl7.fhir.au.core --offline)"
base_line="$(grep -n "hl7.fhir.au.base" <<<"$order_output" | head -1 | cut -d: -f1 || true)"
core_line="$(grep -n "hl7.fhir.au.core" <<<"$order_output" | head -1 | cut -d: -f1 || true)"
if [[ -n "$base_line" && -n "$core_line" && "$base_line" -lt "$core_line" ]]; then
    pass "au.base is processed before au.core"
else
    fail "dependency order was not preserved (base=${base_line:-none} core=${core_line:-none})"
fi

echo
echo "--offline is honoured"
if grep -q "offline mode is enabled" <<<"$order_output"; then
    pass "packages were resolved from cache only"
else
    fail "--offline did not reach the package loader"
fi
if grep -q "Running in offline mode" <<<"$(run fhir:generate --package=hl7.fhir.r4b.core --offline)"; then
    pass "fhir:generate reports offline mode"
else
    fail "fhir:generate did not report offline mode"
fi

echo
echo "fhir:generate-ig falls back to fhir.ig.packages, then to guidance"
if FHIR_IG_PACKAGES=hl7.fhir.au.base run fhir:generate-ig --offline | grep -q "hl7.fhir.au.base"; then
    pass "configured packages are used when --package is omitted"
else
    fail "the fhir.ig.packages fallback was not applied"
fi

set +e
guidance="$(php console.php fhir:generate-ig 2>&1)"
guidance_status=$?
set -e
if [[ "$guidance_status" -ne 0 ]] && grep -q "No packages specified" <<<"$guidance"; then
    pass "no packages anywhere prints guidance and fails"
else
    fail "expected 'No packages specified' and a non-zero exit, got status ${guidance_status}"
fi

if [[ "$WITH_GENERATION" -eq 1 ]]; then
    echo
    echo "a real generation completes (writes into vendor/ardenexal/Models)"
    rm -rf vendor/ardenexal/Models
    if run fhir:generate --package=hl7.fhir.r4b.core | grep -q "generation completed successfully"; then
        generated="$(find vendor/ardenexal/Models -name '*.php' | wc -l)"
        if [[ "$generated" -gt 0 ]]; then
            pass "generated ${generated} PHP files with symfony/console ${console_version}"
        else
            fail "generation reported success but wrote no files"
        fi
    else
        fail "fhir:generate did not complete successfully"
    fi
fi

echo
if [[ "$failures" -gt 0 ]]; then
    echo "${failures} check(s) failed against symfony/console ${console_version}"
    exit 1
fi

echo "all checks passed against symfony/console ${console_version}"
