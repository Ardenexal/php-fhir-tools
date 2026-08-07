<?php

declare(strict_types=1);

/**
 * Compare our validator against the HL7 Java reference validator, per case.
 *
 * Run from the repository root:
 *   php src/Component/Validation/tests/Integration/compare-java-outcomes.php          # R4
 *   php src/Component/Validation/tests/Integration/compare-java-outcomes.php r5
 *   php src/Component/Validation/tests/Integration/compare-java-outcomes.php r4 --above
 *   php src/Component/Validation/tests/Integration/compare-java-outcomes.php r4 --json > before.json
 *
 * Options:
 *   --above   List only cases where we report MORE errors than Java (the false positives).
 *   --below   List only cases where we report FEWER errors than Java (pre-existing gaps).
 *   --all     List every compared case.
 *   --json    Emit machine-readable JSON instead of a table, for before/after diffing.
 *   --family=<label>
 *             Deep-dive one family across every ABOVE case, printing our error messages beside
 *             Java's. Counts alone cannot tell "we report something Java does not" from "we report
 *             the same finding differently", and M02 must not "fix" a family Java agrees with.
 *             Example: --family=constraint:NotBlank
 *
 * Why this exists: FHIRValidatorSpecificationTest asserts against outcomes/ardenexal/, which
 * seed-outcomes.php generates from our own validator's output. That is a regression lock — it tells
 * you behaviour changed, never that behaviour is correct. This script is the conformance oracle.
 * Do not run seed-outcomes.php to make the suite green while any ABOVE case remains.
 */

require_once __DIR__ . '/../../../../../vendor/autoload.php';

use Ardenexal\FHIRTools\Component\Serialization\FHIRSerializationService;
use Ardenexal\FHIRTools\Component\Serialization\FhirVersion;
use Ardenexal\FHIRTools\Component\Validation\Tests\Integration\Oracle\CaseComparison;
use Ardenexal\FHIRTools\Component\Validation\Tests\Integration\Oracle\Classification;
use Ardenexal\FHIRTools\Component\Validation\Tests\Integration\Oracle\ComparisonHarness;
use Ardenexal\FHIRTools\Component\Validation\Tests\Integration\Oracle\OracleValidationServiceFactory;

$args    = array_slice($argv, 1);
$flags   = array_values(array_filter($args, static fn (string $a): bool => str_starts_with($a, '--')));
$posArgs = array_values(array_filter($args, static fn (string $a): bool => !str_starts_with($a, '--')));

$version = match (strtolower($posArgs[0] ?? 'r4')) {
    'r4b'   => FhirVersion::R4B,
    'r5'    => FhirVersion::R5,
    default => FhirVersion::R4,
};

$asJson  = in_array('--json', $flags, true);
$showAll = in_array('--all', $flags, true);
$only    = match (true) {
    in_array('--above', $flags, true) => Classification::Above,
    in_array('--below', $flags, true) => Classification::Below,
    default                           => null,
};

$harness = new ComparisonHarness(
    vendorDir: __DIR__ . '/../../../../../vendor',
    validation: OracleValidationServiceFactory::create($version),
    serialization: FHIRSerializationService::createDefault($version),
    version: $version,
);

if (!$asJson) {
    fwrite(STDERR, "Comparing {$version->value} against Java reference outcomes…\n");
}

$report = $harness->run();

// --family=<label>: side-by-side review of one family. Prints every ABOVE case carrying the label,
// our messages against Java's, so a reviewer can judge false positive vs genuine agreement.
$familyFlag = null;
foreach ($flags as $flag) {
    if (str_starts_with($flag, '--family=')) {
        $familyFlag = substr($flag, strlen('--family='));
        break;
    }
}

if ($familyFlag !== null) {
    $matching = array_values(array_filter(
        $report->byClassification(Classification::Above),
        static fn (CaseComparison $c): bool => in_array($familyFlag, $c->families, true),
    ));

    printf("Family '%s' across %d ABOVE case(s):\n\n", $familyFlag, count($matching));

    foreach ($matching as $c) {
        printf("── %s  (ours %d, java %d)\n", $c->name, $c->ourErrorCount, $c->javaErrorCount);

        echo "   OURS:\n";
        foreach ($c->ourErrorMessages as $i => $m) {
            printf("     [%s] %s\n", $c->families[$i] ?? '?', $m);
        }

        echo "   JAVA:\n";
        if ($c->javaErrorTexts === []) {
            echo "     (no error-severity issues)\n";
        }
        foreach ($c->javaErrorTexts as $t) {
            printf("     %s\n", $t);
        }
        echo "\n";
    }

    exit(0);
}

if ($asJson) {
    echo json_encode([
        'version'          => $version->value,
        'above'            => $report->aboveCount(),
        'equal'            => $report->equalCount(),
        'below'            => $report->belowCount(),
        'compared'         => count($report->comparisons),
        'skipped'          => $report->skippedCount(),
        'skipsByReason'    => $report->skipHistogram(),
        'crashedCases'     => $report->crashedCases(),
        'warningMismatch'  => count($report->warningMismatches()),
        'wallClockSeconds' => round($report->wallClockSeconds, 2),
        'aboveFamilies'    => $report->aboveFamilyHistogram(),
        'cases'            => array_map(static fn (CaseComparison $c): array => [
            'name'            => $c->name,
            'class'           => $c->classification()->value,
            'ours'            => $c->ourErrorCount,
            'oursUnfiltered'  => $c->ourErrorCountUnfiltered,
            'java'            => $c->javaErrorCount,
            'families'        => $c->families,
            'skewedByFilter'  => $c->isSkewedByKnownGapFilter(),
            'oursWarnings'    => $c->ourWarningCount,
            'javaWarnings'    => $c->javaWarningCount,
            'warningClass'    => $c->warningClassification()->value,
        ], $report->comparisons),
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n";

    exit($report->aboveCount() === 0 && $report->crashedCases() === [] ? 0 : 1);
}

$listed = match (true) {
    $showAll       => $report->comparisons,
    $only !== null => $report->byClassification($only),
    default        => $report->byClassification(Classification::Above),
};

if ($listed !== []) {
    printf("%-52s %-6s %6s %6s  %s\n", 'CASE', 'CLASS', 'OURS', 'JAVA', 'FAMILIES');
    echo str_repeat('-', 110) . "\n";

    foreach ($listed as $c) {
        printf(
            "%-52s %-6s %6d %6d  %s\n",
            substr($c->name, 0, 52),
            $c->classification()->value,
            $c->ourErrorCount,
            $c->javaErrorCount,
            implode(', ', array_unique($c->families)),
        );
    }
    echo "\n";
}

printf(
    "ABOVE %d  ·  EQUAL %d  ·  BELOW %d   (compared %d, skipped %d, %.2fs)\n",
    $report->aboveCount(),
    $report->equalCount(),
    $report->belowCount(),
    count($report->comparisons),
    $report->skippedCount(),
    $report->wallClockSeconds,
);

echo 'skips: ';
foreach ($report->skipHistogram() as $reason => $count) {
    printf('%s=%d  ', $reason, $count);
}
echo "\n";

// A case that crashes leaves the comparison set, which lowers ABOVE and reads as an improvement.
// Never let that pass quietly.
$crashed = $report->crashedCases();
if ($crashed !== []) {
    printf(
        "\nCRASHED: %d case(s) threw during validation and were NOT compared.\n"
        . "This lowers the ABOVE count without fixing anything — treat as a regression:\n  %s\n",
        count($crashed),
        implode("\n  ", $crashed),
    );
}

// Warnings do not affect validity, so they do not gate the exit code — but re-seeding while they
// disagree writes an unreviewed count in as correct, so they must be visible.
$warningMismatches = $report->warningMismatches();
if ($warningMismatches !== []) {
    printf(
        "\nWARNING COUNTS differ from Java on %d case(s). These do not block landing, but they DO\n"
        . "block re-seeding — read them before running seed-outcomes.php:\n",
        count($warningMismatches),
    );
    foreach (array_slice($warningMismatches, 0, 15) as $c) {
        printf("  %-52s ours=%-3d java=%-3d\n", substr($c->name, 0, 52), $c->ourWarningCount, $c->javaWarningCount);
    }
    if (count($warningMismatches) > 15) {
        printf("  … and %d more (use --json for the full list)\n", count($warningMismatches) - 15);
    }
}

$exitCode = $report->aboveCount() === 0 && $crashed === [] ? 0 : 1;

$families = $report->aboveFamilyHistogram();
if ($families !== []) {
    echo "\nABOVE families (error violations, largest first):\n";
    foreach ($families as $family => $count) {
        printf("  %-40s %d\n", $family, $count);
    }
}

$skewed = $report->skewedCases();
if ($skewed !== []) {
    printf(
        "\nWARNING: %d case(s) change class depending on isKnownGap() suppression.\n"
        . "Java counts are never filtered, so those comparisons are not apples-to-apples:\n",
        count($skewed),
    );
    foreach ($skewed as $c) {
        printf(
            "  %-52s filtered=%s unfiltered=%s (suppressed %d)\n",
            substr($c->name, 0, 52),
            $c->classification()->value,
            $c->unfilteredClassification()->value,
            $c->suppressedByKnownGap(),
        );
    }
}

exit($exitCode);
