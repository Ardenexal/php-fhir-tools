<?php

declare(strict_types=1);

/*
 * Validation benchmark for runtime profiles.
 *
 * Validates the same blood-pressure fixture two ways: a generated ObservationbpProfile instance
 * against its own URL, and a base Observation against the same URL resolved from the core
 * StructureDefinition at runtime. Both instances get the objects under the profile's rule paths
 * built first, so the profile's rules run and fail. Each run is a fresh PHP process, so "cold" is
 * the first validation in that process (for runtime: package index, resolve, compile and validate)
 * and "warm" is the median of the following validations.
 *
 * Usage:
 *   php src/Component/Validation/tests/Benchmark/runtime-profile-validate.php [--runs=5] [--iterations=50]
 *
 * Uses R5, whose core package ships in vendor with the FHIR test cases.
 * Budget: warm runtime median within 1.5x warm generated median.
 */

use Ardenexal\FHIRTools\Component\Models\R5\Profile\ObservationbpProfile;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\ObservationResource;
use Ardenexal\FHIRTools\Component\Serialization\FhirVersion;
use Ardenexal\FHIRTools\Component\Validation\Profile\PackageDirectoryStructureDefinitionLoader;
use Ardenexal\FHIRTools\Component\Validation\Profile\RuntimeProfileResolver;
use Ardenexal\FHIRTools\Component\Validation\Tests\Integration\Oracle\CoreStructureDefinitionPackages;
use Ardenexal\FHIRTools\Component\Validation\Tests\Integration\Oracle\OracleValidationServiceFactory;
use Ardenexal\FHIRTools\Component\Validation\Tests\Integration\Profile\ProfileRulePathMaterialiser;

require dirname(__DIR__, 5) . '/vendor/autoload.php';

const BP_PROFILE = 'http://hl7.org/fhir/StructureDefinition/bp';

// Parsed by hand: getopt() stops at the first positional argument.
$options = [];
foreach (array_slice($argv, 1) as $arg) {
    if (preg_match('/^--([a-z-]+)(?:=(.*))?$/', $arg, $m) === 1) {
        $options[$m[1]] = $m[2] ?? true;
    }
}
$runs       = max(1, (int) ($options['runs'] ?? 5));
$iterations = max(2, (int) ($options['iterations'] ?? 50));

$median = static function(array $values): float {
    sort($values);
    $count = count($values);

    return $count % 2 === 1 ? $values[intdiv($count, 2)] : ($values[$count / 2 - 1] + $values[$count / 2]) / 2;
};

if (is_string($options['single'] ?? null)) {
    // One measured run of one side, reported as JSON to the parent process.
    $directory = CoreStructureDefinitionPackages::directory(FhirVersion::R5);
    if ($directory === null) {
        fwrite(STDERR, "No R5 core package found.\n");
        exit(2);
    }

    $runtime = $options['single'] === 'runtime';
    $start   = hrtime(true);

    $resolver = new RuntimeProfileResolver(new PackageDirectoryStructureDefinitionLoader($directory, 'R5'));
    $service  = OracleValidationServiceFactory::create(FhirVersion::R5, true, $resolver);

    // Rule paths from a separate resolver, so building the fixture does not warm the measured one.
    $paths = ProfileRulePathMaterialiser::rulePaths(
        (new RuntimeProfileResolver(new PackageDirectoryStructureDefinitionLoader($directory, 'R5')))->resolve(BP_PROFILE, 'R5')->chain,
    );
    $fixture = static function() use ($runtime, $paths): object {
        $instance = $runtime ? new ObservationResource() : new ObservationbpProfile();
        (new ProfileRulePathMaterialiser())->materialise($instance, $paths);

        return $instance;
    };
    $setup = (hrtime(true) - $start) / 1e6;

    $times    = [];
    $findings = 0;
    for ($i = 0; $i < $iterations; ++$i) {
        $instance = $fixture();
        $start    = hrtime(true);
        $report   = $service->validate($instance, [BP_PROFILE]);
        $times[]  = (hrtime(true) - $start) / 1e6;
        $findings = count($report->violations);
    }

    echo json_encode([
        'setup_ms' => $setup,
        'cold_ms'  => $times[0],
        'warm_ms'  => $median(array_slice($times, 1)),
        'findings' => $findings,
        'peak_mb'  => memory_get_peak_usage(true) / 1048576,
    ]), "\n";
    exit(0);
}

$results = ['generated' => [], 'runtime' => []];
for ($run = 0; $run < $runs; ++$run) {
    foreach (array_keys($results) as $side) {
        $output = shell_exec(sprintf('%s %s --single=%s --iterations=%d', escapeshellarg(PHP_BINARY), escapeshellarg(__FILE__), $side, $iterations));
        $result = is_string($output) ? json_decode(trim($output), true) : null;
        if (!is_array($result)) {
            fwrite(STDERR, "Run {$run} ({$side}) failed: " . var_export($output, true) . "\n");
            exit(1);
        }
        $results[$side][] = $result;
    }
}

printf("Blood-pressure profile, R5, %d runs x %d validations\n\n", $runs, $iterations);
printf("%-10s %-24s %-24s %-10s %s\n", 'side', 'cold ms (median, range)', 'warm ms (median, range)', 'findings', 'peak MB');
$warm = [];
foreach ($results as $side => $sideRuns) {
    $cold         = array_column($sideRuns, 'cold_ms');
    $warm[$side]  = array_column($sideRuns, 'warm_ms');
    printf(
        "%-10s %-24s %-24s %-10d %.1f\n",
        $side,
        sprintf('%.2f (%.2f-%.2f)', $median($cold), min($cold), max($cold)),
        sprintf('%.3f (%.3f-%.3f)', $median($warm[$side]), min($warm[$side]), max($warm[$side])),
        $sideRuns[0]['findings'],
        max(array_column($sideRuns, 'peak_mb')),
    );
}

$ratio = $median($warm['runtime']) / $median($warm['generated']);
printf("\nwarm runtime / generated: %.2fx (budget 1.50x) %s\n", $ratio, $ratio <= 1.5 ? 'WITHIN BUDGET' : 'OVER BUDGET');
exit($ratio <= 1.5 ? 0 : 1);
