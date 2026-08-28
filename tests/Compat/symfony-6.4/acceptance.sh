#!/usr/bin/env bash
#
# Acceptance checks for the fhir:generate / fhir:generate-ig commands against the installed
# Symfony versions. Intended for the 6.4 lower bound of every symfony/* package the component
# constrains to ^6.4, but it runs unchanged on 7.x, which is what makes the two comparable.
#
# Usage:
#   composer install --working-dir=tests/Compat/symfony-6.4
#   tests/Compat/symfony-6.4/acceptance.sh [--with-generation]
#
# --with-generation additionally runs two real generations: R4B core through fhir:generate and
# hl7.fhir.au.base through fhir:generate-ig. Both need network access and write into this
# harness's vendor/ardenexal/Models/ sandbox. Without it the script stays offline and only
# exercises option parsing plus the guarded failure paths.
#
# Both are run because they are separate pipelines reaching different generators: fhir:generate-ig
# is the only one that exercises the profile and extension generators, and it is the invocation
# the pascal() breakage was reported against.

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

installed_version() {
    php -r '
        $packages = json_decode(file_get_contents("vendor/composer/installed.json"), true)["packages"];
        foreach ($packages as $package) {
            if ($package["name"] === $argv[1]) {
                echo ltrim($package["version"], "v");
            }
        }
    ' "$1"
}

console_version="$(installed_version symfony/console)"
string_version="$(installed_version symfony/string)"

echo "symfony/console ${console_version}"
echo "symfony/string  ${string_version}"

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

# The same disclosure for symfony/string. pascal() arrived in 7.3 and kebab() in 7.2, but the
# component declares ^6.4|^7.0, so both are absent across 6.4 through 7.2. Calling either threw
# on every StructureDefinition and produced no classes at all. Printed rather than asserted,
# because the harness must stay runnable on 7.x for the output comparison below.
echo
echo "string method availability"
php -r '
    require "vendor/autoload.php";
    foreach (["camel", "snake", "title", "pascal", "kebab"] as $method) {
        printf("  %-10s %s%s", $method, method_exists("Symfony\\Component\\String\\AbstractString", $method) ? "present" : "absent", PHP_EOL);
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

    # fhir:generate-ig runs the profile and extension generators, which the R4B run above never
    # reaches, and it is the invocation the pascal() breakage was reported against (108 failed
    # StructureDefinitions, no output directory).
    #
    # The error count is asserted alongside the file count, not just the exit status: both
    # commands report per-definition failures and continue, so a partial outage still leaves a
    # populated output directory behind. Under the pascal() bug fhir:generate exited non-zero
    # having written 103 of its usual 1700 files, which a "did it write anything" check passes.
    echo
    echo "a real IG generation completes with no per-definition errors"
    rm -rf vendor/ardenexal/Models
    ig_output="$(run fhir:generate-ig --package=hl7.fhir.au.base)"
    ig_errors="$(grep -c "IG_GENERATION_ERROR" <<<"$ig_output" || true)"

    if [[ "$ig_errors" -eq 0 ]]; then
        pass "no IG_GENERATION_ERROR lines"
    else
        fail "${ig_errors} IG_GENERATION_ERROR line(s):"
        grep -m3 "IG_GENERATION_ERROR" <<<"$ig_output" | sed 's/^/        /'
    fi

    ig_generated="$(find vendor/ardenexal/Models -name '*.php' 2>/dev/null | wc -l)"
    if [[ "$ig_generated" -gt 0 ]]; then
        pass "generated ${ig_generated} IG PHP files with symfony/string ${string_version}"
    else
        fail "the IG output directory was never created or is empty"
    fi

    # Spot-checks the reason hl7.fhir.au.base is generated at all: au-ihi must come out as a
    # constrained Identifier pinning the IHI namespace. A class-naming regression would move or
    # rename this file rather than fail, so the path is asserted as well as the constant.
    ihi_profile="vendor/ardenexal/Models/src/IG/R4/AuBase/Profile/AUIHIProfile.php"
    if [[ -f "$ihi_profile" ]] && grep -q "http://ns.electronichealth.net.au/id/hi/ihi/1.0" "$ihi_profile"; then
        pass "au-ihi generated AUIHIProfile with the pinned IHI system"
    else
        fail "expected ${ihi_profile} to pin http://ns.electronichealth.net.au/id/hi/ihi/1.0"
    fi
fi

echo
if [[ "$failures" -gt 0 ]]; then
    echo "${failures} check(s) failed against symfony/console ${console_version} + symfony/string ${string_version}"
    exit 1
fi

echo "all checks passed against symfony/console ${console_version} + symfony/string ${string_version}"
