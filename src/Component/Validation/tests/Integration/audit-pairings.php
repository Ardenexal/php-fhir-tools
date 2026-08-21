<?php

declare(strict_types=1);

/**
 * Review how the missing-finding count was arrived at, rather than what it came to.
 *
 * Run from the repository root:
 *   php src/Component/Validation/tests/Integration/audit-pairings.php              # every version
 *   php src/Component/Validation/tests/Integration/audit-pairings.php r5           # one version
 *   php src/Component/Validation/tests/Integration/audit-pairings.php --rule=cardinality
 *   php src/Component/Validation/tests/Integration/audit-pairings.php --collisions  # ordering only
 *
 * Why this exists: `compare-java-outcomes.php` reports how many reference findings have no counterpart in
 * ours. That number is produced by two judgements, and neither is visible in the number itself.
 *
 * The first is **pairing**: deciding that a finding of ours is the reference validator's finding in
 * different words. Getting that wrong in the generous direction erases a real gap, and the erased finding
 * is by definition absent from the missing list — so no amount of staring at the gap report can catch it.
 * The only way to check precision is to read the pairings that were claimed, which is what this prints.
 * Every defect found while building the measurement was found this way, and none of them were visible in
 * the totals.
 *
 * The second is **ordering**: a finding whose text matches several capability signatures is labelled by
 * whichever comes first in `MissingFindingClassifier::SIGNATURES`. `--collisions` lists exactly those
 * findings, so the placements that are load-bearing can be told from the ones that would survive any
 * reordering. Each collision listed here should have a pinning test in
 * `tests/Unit/MissingFindingClassifierTest.php`; one that does not is an ordering nothing protects.
 *
 * This is a review tool, not a check. It has no pass or fail and always exits zero — the judgements it
 * surfaces are for a person to make. The assertions that follow from them live in the test suite.
 */

require_once __DIR__ . '/../../../../../vendor/autoload.php';

use Ardenexal\FHIRTools\Component\Serialization\FHIRSerializationService;
use Ardenexal\FHIRTools\Component\Serialization\FhirVersion;
use Ardenexal\FHIRTools\Component\Validation\Tests\Integration\Oracle\ComparisonHarness;
use Ardenexal\FHIRTools\Component\Validation\Tests\Integration\Oracle\MissingFindingClassifier;
use Ardenexal\FHIRTools\Component\Validation\Tests\Integration\Oracle\OracleValidationServiceFactory;

/** How many examples to show per rule before summarising, so one noisy case cannot fill the output. */
const SPREAD_PER_RULE = 10;

$args    = array_slice($argv, 1);
$flags   = array_values(array_filter($args, static fn (string $a): bool => str_starts_with($a, '--')));
$posArgs = array_values(array_filter($args, static fn (string $a): bool => !str_starts_with($a, '--')));

$ruleFilter = null;
foreach ($flags as $flag) {
    if (str_starts_with($flag, '--rule=')) {
        $ruleFilter = substr($flag, strlen('--rule='));
    }
}

$collisionsOnly = in_array('--collisions', $flags, true);

$versions = match (strtolower($posArgs[0] ?? '')) {
    'r4'    => ['R4' => FhirVersion::R4],
    'r4b'   => ['R4B' => FhirVersion::R4B],
    'r5'    => ['R5' => FhirVersion::R5],
    default => ['R4' => FhirVersion::R4, 'R4B' => FhirVersion::R4B, 'R5' => FhirVersion::R5],
};

/** @var array<string, list<string>> $byRule the pairing rule that decided each claim, so precision can be read per rule */
$byRule = [];
/** @var array<string, list<string>> $collisions keyed by which label won over which, so a flip is legible */
$collisions = [];
$pairCount  = 0;

foreach ($versions as $tag => $version) {
    $harness = new ComparisonHarness(
        vendorDir: __DIR__ . '/../../../../../vendor',
        validation: OracleValidationServiceFactory::create($version),
        serialization: FHIRSerializationService::createDefault($version),
        version: $version,
    );

    fwrite(STDERR, "Auditing {$tag}…\n");
    $report = $harness->run();

    foreach ($report->comparisons as $case) {
        foreach ($case->delta->matched as $pair) {
            ++$pairCount;
            $byRule[$pair['rule']][] = sprintf(
                "[%s/%s]\n      JAVA: %s\n      OURS: %s%s",
                $tag,
                $case->name,
                oneLine($pair['java']),
                oneLine($pair['ours']),
                $pair['ourPath'] === '' ? '' : sprintf(' (at %s)', $pair['ourPath']),
            );
        }

        // Ordering only decides a label when the text matches more than one signature. Collect those, so
        // a reader can tell a load-bearing placement from an incidental one.
        foreach ($case->delta->findings as $text) {
            $hits = matchingLabels($text);
            if (count($hits) > 1) {
                $collisions[implode(' BEATS ', $hits)][] = sprintf('[%s/%s] %s', $tag, $case->name, oneLine($text));
            }
        }
    }
}

if (!$collisionsOnly) {
    printf("\n=== %d pairing(s) claimed, by rule ===\n", $pairCount);
    foreach ($byRule as $rule => $entries) {
        printf("  %-16s %d\n", $rule, count($entries));
    }

    if ($byRule === []) {
        echo "  (none — every reference finding is counted as missing)\n";
    }

    foreach ($byRule as $rule => $entries) {
        if ($ruleFilter !== null && $rule !== $ruleFilter) {
            continue;
        }

        printf("\n\n########## %s (%d total) ##########\n", strtoupper($rule), count($entries));

        // One example per distinct reference wording. Without this, a Bundle repeating one finding forty
        // times crowds out every other wording the rule pairs, and the sample stops being a sample.
        $distinct = [];
        foreach ($entries as $entry) {
            // Blank the quoted values so one wording repeated over many instances collapses to one row.
            $wording = (string) preg_replace("/'[^']*'/", "''", explode("\n", $entry)[1] ?? $entry);
            $distinct[$wording] ??= $entry;
        }

        foreach (array_slice(array_values($distinct), 0, SPREAD_PER_RULE) as $index => $entry) {
            printf("\n  %2d. %s\n", $index + 1, $entry);
        }

        if (count($distinct) > SPREAD_PER_RULE) {
            printf("\n  … and %d more distinct wording(s)\n", count($distinct) - SPREAD_PER_RULE);
        }
    }
}

printf("\n\n=== %d signature collision(s) decided by SIGNATURES order ===\n", count($collisions));
if ($collisions === []) {
    echo "  (none — no finding matches more than one capability, so order decides nothing)\n";
}

foreach ($collisions as $contest => $examples) {
    printf("\n  %s  (%d finding(s))\n", $contest, count($examples));
    printf("    e.g. %s\n", $examples[0]);
}

echo "\nEach collision above needs a pinning test in tests/Unit/MissingFindingClassifierTest.php.\n";

exit(0);

/** Collapse a reference message onto one line so a multi-line diagnostic stays readable in a list. */
function oneLine(string $text): string
{
    return substr(str_replace("\n", ' ', $text), 0, 150);
}

/**
 * Every capability whose signatures match this finding, not merely the first.
 *
 * `MissingFindingClassifier::classify()` stops at the first hit, which is the right behaviour and the
 * reason a second opinion is needed here: only by matching against all of them can a reader see which
 * answers depend on order.
 *
 * @return list<string>
 */
function matchingLabels(string $javaText): array
{
    $haystack = strtolower($javaText);
    $hits     = [];

    foreach (MissingFindingClassifier::SIGNATURES as $label => $signatures) {
        foreach ($signatures as $signature) {
            if (str_contains($haystack, $signature)) {
                $hits[] = $label;
                break;
            }
        }
    }

    return $hits;
}
