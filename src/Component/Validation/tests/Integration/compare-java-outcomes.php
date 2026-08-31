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
 *   --below --family=<label>
 *             The same review in the other direction: every case missing a finding of one
 *             *capability*, with the reference findings we do not report beside everything we do
 *             report on that case. This is how a pairing gets judged by eye.
 *             Example: --below --family=terminology:display
 *
 * Why this exists: FHIRValidatorSpecificationTest asserts against outcomes/ardenexal/, which
 * seed-outcomes.php generates from our own validator's output. That is a regression lock — it tells
 * you behaviour changed, never that behaviour is correct. This script is the conformance oracle.
 * Do not run seed-outcomes.php to make the suite green while any ABOVE case remains.
 *
 * ## Read MISSING, not BELOW
 *
 * `BELOW` counts cases where Java reports more errors than we do, which is not the number of checks
 * we lack. It overstates — `R4.Observation-ex-pain` reads two short when one of the two is our own
 * `This value should not be blank.` worded differently — and it also hides cases that agree on the
 * total while reporting different things. MISSING pairs the two sides finding by finding and counts
 * only what is left over. Size work off MISSING and its capability labels; BELOW stays on the
 * summary line because the specification suite still classifies on counts.
 */

require_once __DIR__ . '/../../../../../vendor/autoload.php';

use Ardenexal\FHIRTools\Component\Serialization\FHIRSerializationService;
use Ardenexal\FHIRTools\Component\Serialization\FhirVersion;
use Ardenexal\FHIRTools\Component\Validation\Tests\Integration\Oracle\CaseComparison;
use Ardenexal\FHIRTools\Component\Validation\Tests\Integration\Oracle\Classification;
use Ardenexal\FHIRTools\Component\Validation\Tests\Integration\Oracle\ComparisonHarness;
use Ardenexal\FHIRTools\Component\Validation\Tests\Integration\Oracle\OracleValidationServiceFactory;
use Ardenexal\FHIRTools\Component\Validation\Tests\Integration\Oracle\UnreadCase;

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
    serialization: FHIRSerializationService::createWithIG(version: $version, includeBaseProfiles: true),
    version: $version,
    validationWithoutExtensionResolution: OracleValidationServiceFactory::create($version, resolveExtensions: false),
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

// With --below, the label names a missing *capability* rather than one of our constraint families, so
// the review is inverted: the left column is everything we report on the case and the right column is
// only what Java reports that we do not. Judging a pairing needs both, which a MISSING count cannot show.
if ($familyFlag !== null && $only === Classification::Below) {
    $matching = $report->casesNeeding($familyFlag);
    $unreadOn = $report->unreadNeeding($familyFlag);

    printf(
        "Capability '%s': %d compared case(s), %d unread case(s):\n\n",
        $familyFlag,
        count($matching),
        count($unreadOn),
    );

    foreach ($matching as $c) {
        $missingHere = $c->delta->findingsFor($familyFlag);

        printf(
            "── %s  (ours %d, java %d, missing %d of which %d here)\n",
            $c->name,
            $c->ourErrorCount,
            $c->javaErrorCount,
            $c->delta->count(),
            count($missingHere),
        );

        echo "   WE REPORT:\n";
        if ($c->ourErrorMessages === []) {
            echo "     (nothing)\n";
        }
        foreach ($c->ourErrorMessages as $i => $m) {
            printf("     [%s] %s — %s\n", $c->families[$i] ?? '?', $c->ourErrorPaths[$i] ?? '?', $m);
        }

        echo "   JAVA REPORTS, UNPAIRED:\n";
        foreach ($missingHere as $t) {
            printf("     %s\n", $t);
        }
        echo "\n";
    }

    foreach ($unreadOn as $u) {
        printf("── %s  (UNREAD — nothing of ours to compare)\n", $u->name);
        printf("   REJECTED: %s\n", str_replace("\n", ' ', $u->failureMessage));
        echo "   JAVA REPORTS, UNPAIRED:\n";
        foreach ($u->delta->findingsFor($familyFlag) as $t) {
            printf("     %s\n", $t);
        }
        echo "\n";
    }

    exit(0);
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
        // UNREAD belongs in the JSON as well as the table: --json is what the re-baselining workflow
        // diffs (`--json > before.json`), so a section that existed only in the human printer would be
        // invisible to the exact process it was added to serve.
        'unread'           => $report->unreadCount(),
        'unreadJavaErrors' => $report->unreadJavaErrorCount(),
        'unreadCases'      => array_map(static fn (UnreadCase $c): array => [
            'name'         => $c->name,
            'java'         => $c->javaErrorCount,
            'javaWarnings' => $c->javaWarningCount,
            'failure'      => $c->failureMessage,
            'missing'      => $c->delta->count(),
            'missingBy'    => $c->delta->labelHistogram(),
        ], $report->unreadByImpact()),
        // The headline the parity work is sized against. Ahead of the case classes in the payload
        // because reading `below` first is the mistake this measurement exists to stop.
        'missing'          => $report->missingFindingCount(),
        'missingOpen'      => $report->openMissingCount(),
        'missingDeclared'  => $report->declaredMissingCount(),
        'declaredByReason' => $report->declaredByReason(),
        'missingBy'        => $report->missingFindingHistogram(),
        'crashedCases'     => $report->crashedCases(),
        'warningMismatch'  => count($report->warningMismatches()),
        'wallClockSeconds' => round($report->wallClockSeconds, 2),
        'aboveFamilies'    => $report->aboveFamilyHistogram(),
        'cases'            => array_map(static fn (CaseComparison $c): array => [
            'name'            => $c->name,
            'class'           => $c->classification()->value,
            'ours'            => $c->ourErrorCount,
            'java'            => $c->javaErrorCount,
            'families'        => $c->families,
            'oursWarnings'    => $c->ourWarningCount,
            'javaWarnings'    => $c->javaWarningCount,
            'warningClass'    => $c->warningClassification()->value,
            'missing'         => $c->delta->count(),
            'missingBy'       => $c->delta->labelHistogram(),
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

// MISSING leads because it is the number work is sized against; the case classes follow it because the
// specification suite still asserts on counts. Reading BELOW as the gap is the error being corrected —
// see this file's "Read MISSING, not BELOW".
printf(
    "MISSING %d reference finding(s) with no counterpart in ours (across %d compared + %d unread case(s))\n"
    . "  of which OPEN %d  ·  DECLARED %d (blocked outside this codebase; see below)\n",
    $report->missingFindingCount(),
    count($report->comparisons),
    $report->unreadCount(),
    $report->openMissingCount(),
    $report->declaredMissingCount(),
);

printf(
    "ABOVE %d  ·  EQUAL %d  ·  BELOW %d  ·  UNREAD %d   (compared %d, skipped %d, %.2fs)\n",
    $report->aboveCount(),
    $report->equalCount(),
    $report->belowCount(),
    $report->unreadCount(),
    count($report->comparisons),
    $report->skippedCount(),
    $report->wallClockSeconds,
);

echo 'skips: ';
foreach ($report->skipHistogram() as $reason => $count) {
    printf('%s=%d  ', $reason, $count);
}
echo "\n";

// UNREAD is a standing quantity, not a delta. ABOVE/EQUAL/BELOW describe the cases we could read;
// these are the ones we could not, and until now they were visible only as a skip tally that moves.
// The Java error total is the point: it converts "17 cases missing" into "how much reference
// behaviour is going unmeasured", which is what decides whether this is worth working on next.
if ($report->unreadCount() > 0) {
    printf(
        "\nUNREAD: %d case(s) the deserializer rejected, hiding %d Java error report(s).\n"
        . "These are in NO class — not ABOVE, not BELOW — so they cannot show up as a regression.\n",
        $report->unreadCount(),
        $report->unreadJavaErrorCount(),
    );

    foreach (array_slice($report->unreadByImpact(), 0, 10) as $unreadCase) {
        printf(
            "  %-44s java=%-4d %s\n",
            substr($unreadCase->name, 0, 44),
            $unreadCase->javaErrorCount,
            substr(str_replace("\n", ' ', $unreadCase->failureMessage), 0, 70),
        );
    }

    if ($report->unreadCount() > 10) {
        printf("  … and %d more (use --json for the full list)\n", $report->unreadCount() - 10);
    }
}

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

// The capability breakdown, which is what M02 picks its next target from. The totals sum to MISSING by
// construction — `other` is a real label, not a discard — so a growing `other` means a signature is
// missing from MissingFindingClassifier, never that findings were lost.
// Named, not merely counted. A limitation whose reason is not written down is indistinguishable from a
// gap nobody got round to, which is the confusion this section exists to remove.
$declared = $report->declaredByReason();
if ($declared !== []) {
    printf("\nDECLARED LIMITATIONS (%d finding(s) — not open work):\n", $report->declaredMissingCount());
    foreach ($declared as $reason => $count) {
        printf("  %4d  %s\n", $count, $reason);
    }
}

$missingBy = $report->missingFindingHistogram();
if ($missingBy !== []) {
    printf(
        "\nMISSING by capability (largest first, sums to %d). Review one with:\n"
        . "  --below --family=<label>\n",
        $report->missingFindingCount(),
    );
    foreach ($missingBy as $label => $count) {
        printf("  %-40s %d\n", $label, $count);
    }

    // The distribution is far more uneven than the total suggests, so print it. Sizing work off the sum
    // alone would credit one encoding fix with a hundred-plus findings.
    $byCase = $report->missingByCase();
    echo "\nMISSING concentrated in these cases (largest first):\n";
    foreach (array_slice($byCase, 0, 10, true) as $caseName => $count) {
        printf("  %-46s %d\n", substr($caseName, 0, 46), $count);
    }
    printf(
        "  (%d case(s) missing at least one finding; top 10 hold %d of %d)\n",
        count($byCase),
        array_sum(array_slice($byCase, 0, 10, true)),
        $report->missingFindingCount(),
    );
}

exit($exitCode);
