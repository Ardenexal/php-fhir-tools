#!/usr/bin/env bash
#
# Classifies a list of changed paths into the CI areas a change could plausibly break,
# so jobs that cannot fail for a given change do not run. Reads paths on stdin (one per
# line), writes `area=true|false` lines on stdout in GITHUB_OUTPUT format.
#
# Called by the `changes` job in pr.yml and package-integrity.yml. It exists as a script
# rather than inline YAML for one reason: this logic decides whether required checks run,
# and a script can be run locally against a real path list. See tests at the bottom of
# this file's sibling, changed-areas-test.sh.
#
#   Areas
#     code      Anything that is not purely documentation. Gates style, static analysis,
#               the test matrix, benchmarks and the Symfony lower-bound harness.
#     codegen   Inputs to `fhir:generate`, i.e. what the FHIR model generation and
#               model-drift legs actually consume.
#     deps      Dependency manifests, for `composer audit`.
#     packages  Split-package sources and manifests, for the isolation installs.
#
# FAIL-SAFE DIRECTION: every ambiguous case must resolve to running the job. An area
# wrongly false is a green check over untested code; an area wrongly true only costs
# runner minutes. Hence the doc list below is a deny-list (unknown paths are significant
# by default) and empty input means "run everything", because a change set with no files
# means the caller failed to fetch it, never that nothing changed.
set -euo pipefail

code=false
codegen=false
deps=false
packages=false

all_true() {
    printf 'code=true\ncodegen=true\ndeps=true\npackages=true\n'
}

if [ "${1:-}" = '--all' ]; then
    all_true
    exit 0
fi

# True when the path cannot affect the outcome of any job in any workflow. Verified by
# grepping executable PHP for *.md string literals, not just for read calls on the same
# line: every hit outside recipe/ is either a docblock or a test writing its own temp
# fixture, so project markdown is inert.
#
# recipe/ is the exception, and is listed first because the arms below would otherwise
# swallow it. FlexRecipeTest::testDocumentationFiles reads recipe/fhir-bundle/1.0/README.md
# and asserts it is non-empty, and scripts/validate-recipe.php reads the same tree, so
# editing recipe markdown really can fail the integration suite and recipe-validation.
is_documentation() {
    case "$1" in
        recipe/*) return 1 ;;
        *.md) return 0 ;;
        docs/* | LICENSE) return 0 ;;
        .goat-flow/* | .ai/* | mate/*) return 0 ;;
        .vscode/* | .devcontainer/* | .idea/*) return 0 ;;
        .github/CODEOWNERS | .github/ISSUE_TEMPLATE/*) return 0 ;;
        .editorconfig | .mcp.json | .worktreeinclude | .gruff-php.yaml) return 0 ;;
    esac

    return 1
}

# Note what is NOT in the list above. .gitignore looks like editor furniture but decides two
# checks: the model-drift step runs `git status --porcelain -- src/Component/Models/src` and
# main.yml closes with a bare `git status --porcelain`, and both honour it. A new ignore rule
# can turn either green or red on its own, so a .gitignore-only change is significant.
#
# The `|| [ -n "$path" ]` below matters as much as the classification: `while read` alone
# discards a final line with no trailing newline, which silently dropped the last path and
# reported the change set as documentation.
seen_any=false

while IFS= read -r path || [ -n "$path" ]; do
    [ -n "$path" ] || continue
    seen_any=true

    if is_documentation "$path"; then
        continue
    fi

    code=true

    # First match wins, so the narrow arms precede `src/*`. Attribution for codegen is
    # not a guess: CodeGeneration requires exactly one sibling (ardenexal/fhir-metadata,
    # per its composer.json) and generation runs through demo/bin/console, so the Bundle
    # wiring and the demo app are inputs too. Models/ and CdaModels/ are the drift
    # targets. pint.json is in the set because every generate-models-* script ends in
    # @lint:models -- a Pint rule change rewrites generated files and shows up as drift.
    case "$path" in
        .github/*)
            codegen=true
            deps=true
            packages=true
            ;;
        composer.json | */composer.json)
            codegen=true
            deps=true
            packages=true
            ;;
        pint.json | demo/*)
            codegen=true
            ;;
        src/Bundle/*)
            codegen=true
            packages=true
            ;;
        src/Component/CodeGeneration/* | src/Component/Metadata/* | src/Component/Models/* | src/Component/CdaModels/*)
            codegen=true
            packages=true
            ;;
        src/*)
            packages=true
            ;;
    esac
done

if [ "$seen_any" = false ]; then
    echo 'changed-areas: empty change set, assuming every area changed' >&2
    all_true
    exit 0
fi

printf 'code=%s\ncodegen=%s\ndeps=%s\npackages=%s\n' "$code" "$codegen" "$deps" "$packages"
