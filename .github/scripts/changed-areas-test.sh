#!/usr/bin/env bash
#
# Tests for changed-areas.sh. Run it directly: .github/scripts/changed-areas-test.sh
#
# This is the only place the change-detection logic can be checked before it decides
# whether a required check runs, so add a case here for every path pattern you add there.
set -uo pipefail

cd "$(dirname "$0")/../.." || exit 1
script=.github/scripts/changed-areas.sh
failures=0

# expect <name> <expected: code,codegen,deps,packages> <path>...
expect() {
    local name=$1 want=$2
    shift 2

    local got
    got=$(printf '%s\n' "$@" | "$script" | sed 's/^[a-z]*=//' | paste -sd, -)

    if [ "$got" = "$want" ]; then
        printf 'ok   %s\n' "$name"
    else
        printf 'FAIL %s\n       want %s\n       got  %s\n' "$name" "$want" "$got"
        failures=$((failures + 1))
    fi
}

expect 'docs-only: a single readme'        false,false,false,false README.md
expect 'docs-only: the release paperwork'  false,false,false,false CHANGELOG.md README.md docs/getting-started/packages.md
expect 'docs-only: a component readme'     false,false,false,false src/Component/CdaModels/README.md
expect 'docs-only: agent instructions'     false,false,false,false CLAUDE.md AGENTS.md .goat-flow/architecture.md
expect 'docs-only: editor files'           false,false,false,false .editorconfig .vscode/settings.json .idea/x.xml

# FlexRecipeTest::testDocumentationFiles asserts this file is non-empty.
expect 'recipe markdown is NOT docs'       true,false,false,false recipe/fhir-bundle/1.0/README.md
expect 'recipe payload'                    true,false,false,false recipe/fhir-bundle/1.0/manifest.json
expect 'serialization source'              true,false,false,true src/Component/Serialization/src/FHIRSerializationService.php
expect 'fhirpath source'                   true,false,false,true src/Component/FHIRPath/src/Evaluator.php
expect 'codegen source'                    true,true,false,true src/Component/CodeGeneration/src/Generator/OperationClassNamer.php
expect 'metadata source (codegen sibling)' true,true,false,true src/Component/Metadata/src/Attribute/Valid.php
expect 'generated models'                  true,true,false,true src/Component/Models/src/R4/Resource/PatientResource.php
expect 'bundle wiring'                     true,true,false,true src/Bundle/FHIRBundle/DependencyInjection/Configuration.php
expect 'demo console app'                  true,true,false,false demo/config/services.yaml
expect 'pint config rewrites generated'    true,true,false,false pint.json
expect 'root manifest'                     true,true,true,true composer.json
expect 'component manifest'                true,true,true,true src/Component/Validation/composer.json
expect 'a workflow sees its own config'    true,true,true,true .github/workflows/pr.yml
expect 'the classifier sees itself'        true,true,true,true .github/scripts/changed-areas.sh
expect 'phpunit config'                    true,false,false,false phpunit.dist.xml
expect 'root integration test'             true,false,false,false tests/Integration/OperationDocsExamplesTest.php
expect 'benchmarks'                        true,false,false,false bench/SerializationBench.php

expect 'docs plus one source file'         true,false,false,true README.md src/Component/Serialization/src/X.php
expect 'unknown new top-level path'        true,false,false,false plugins/whatever.php

# .gitignore drives `git status --porcelain`, which the model-drift step and main.yml's
# closing step both depend on, so it is not editor furniture.
expect 'gitignore is significant'          true,false,false,false .gitignore
expect 'gitignore alongside docs'          true,false,false,false README.md .gitignore

# A rename arrives as both paths (changed-paths.sh emits previous_filename too). Moving the
# recipe README into docs/ must stay significant on the strength of the path it left.
expect 'rename out of recipe/'             true,false,false,false docs/readme.md recipe/fhir-bundle/1.0/README.md

# `while read` alone drops a final line with no trailing newline. The helper above always
# adds one, so this case has to bypass it.
got_no_newline=$(printf 'README.md\nsrc/Component/Serialization/src/X.php' | "$script" | sed 's/^[a-z]*=//' | paste -sd, -)
if [ "$got_no_newline" = 'true,false,false,true' ]; then
    printf 'ok   a list with no trailing newline keeps its last path\n'
else
    printf 'FAIL a list with no trailing newline keeps its last path\n       got %s\n' "$got_no_newline"
    failures=$((failures + 1))
fi

got_all=$(printf 'README.md\n' | "$script" --all | sed 's/^[a-z]*=//' | paste -sd, -)
if [ "$got_all" = 'true,true,true,true' ]; then
    printf 'ok   --all overrides the path list\n'
else
    printf 'FAIL --all overrides the path list\n       got %s\n' "$got_all"
    failures=$((failures + 1))
fi

got_empty=$(printf '' | "$script" 2>/dev/null | sed 's/^[a-z]*=//' | paste -sd, -)
if [ "$got_empty" = 'true,true,true,true' ]; then
    printf 'ok   an empty change set runs everything\n'
else
    printf 'FAIL an empty change set runs everything\n       got %s\n' "$got_empty"
    failures=$((failures + 1))
fi

if [ "$failures" -ne 0 ]; then
    printf '\n%s test(s) failed\n' "$failures"
    exit 1
fi

printf '\nall tests passed\n'
