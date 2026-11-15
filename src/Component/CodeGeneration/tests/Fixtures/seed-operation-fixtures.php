<?php

declare(strict_types=1);

/**
 * Rebuild the committed operation-manifest and type-index fixtures from the FHIR package cache.
 *
 * Run from the repository root:
 *   php src/Component/CodeGeneration/tests/Fixtures/seed-operation-fixtures.php          # all versions
 *   php src/Component/CodeGeneration/tests/Fixtures/seed-operation-fixtures.php r5       # one version
 *
 * Requires the package cache at `demo/var/cache/dev/.fhir/`. It is gitignored, so populate it with
 * `composer run generate-models-all` (or any `fhir:generate` run) first.
 *
 * Existing fixture files are overwritten. **Review the diff** — these fixtures are the specification
 * data four tests assert against, so an unexpected change here means either the packages moved or the
 * extractor did, and the two need telling apart. `OperationFixturesMatchPackagesTest` fails whenever
 * the committed files and a fresh extraction disagree, which is the signal to run this.
 *
 * Mirrors `src/Component/Validation/tests/Integration/seed-outcomes.php` in shape deliberately —
 * one convention for "regenerate a committed fixture from an external source".
 */

require_once __DIR__ . '/../../../../../vendor/autoload.php';
require_once __DIR__ . '/OperationFixtureExtractor.php';

use Ardenexal\FHIRTools\Component\CodeGeneration\Tests\Fixtures\OperationFixtureExtractor;

$extractor = new OperationFixtureExtractor();
$available = $extractor->availableVersions();

if ($available === []) {
    fwrite(\STDERR, "No FHIR packages found in demo/var/cache/dev/.fhir/.\n"
        . "Run `composer run generate-models-all` first to populate the cache.\n");

    exit(1);
}

$requested = $argv[1] ?? null;

if (is_string($requested)) {
    $requested = strtolower($requested);

    if (!in_array($requested, $available, true)) {
        fwrite(\STDERR, sprintf("Version \"%s\" is not in the cache. Available: %s\n", $requested, implode(', ', $available)));

        exit(1);
    }

    $available = [$requested];
}

$manifestDir = __DIR__ . '/OperationManifests';
$indexDir    = __DIR__ . '/TypeIndex';

foreach ($available as $version) {
    $manifest = $extractor->buildOperationManifest($version);
    $index    = $extractor->buildTypeIndex($version);

    $operations = count(array_filter($manifest, static fn (array $d): bool => ($d['kind'] ?? null) === 'operation'));

    write($manifestDir . '/' . $version . '-operations.json', $manifest);
    write($indexDir . '/' . $version . '-type-index.json', $index);

    printf(
        "%-4s %d definitions (%d kind=operation, %d other) · %d types\n",
        strtoupper($version),
        count($manifest),
        $operations,
        count($manifest) - $operations,
        count($index),
    );
}

echo "Done. Review the diff before committing.\n";

/**
 * @param array<string, mixed> $data
 */
function write(string $path, array $data): void
{
    // Pretty-printed and unescaped so the committed fixture is reviewable as a diff rather than as one
    // long line; trailing newline so the file is POSIX-clean.
    $json = json_encode($data, \JSON_PRETTY_PRINT | \JSON_UNESCAPED_SLASHES | \JSON_UNESCAPED_UNICODE | \JSON_THROW_ON_ERROR);

    // PHP's JSON_PRETTY_PRINT is hard-wired to four spaces; the committed fixtures use two. Re-indent
    // rather than reformat the fixtures, so re-running this script against unchanged packages produces
    // **no diff at all** — that no-op is what makes OperationFixturesMatchPackagesTest's signal
    // trustworthy. A whitespace-only rewrite of 14,000 lines would bury a real change.
    $json = (string) preg_replace_callback(
        '/^(?: {4})+/m',
        static fn (array $m): string => str_repeat(' ', strlen($m[0]) / 2),
        $json,
    );

    if (file_put_contents($path, $json . "\n") === false) {
        fwrite(\STDERR, sprintf("Could not write %s\n", $path));

        exit(1);
    }
}
