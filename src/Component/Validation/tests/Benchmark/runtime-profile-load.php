<?php

declare(strict_types=1);

/*
 * Load benchmark for runtime profile resolution.
 *
 * Measures what it costs to point a PackageDirectoryStructureDefinitionRegistry at a full core
 * package and resolve one profile chain (default: the core blood-pressure profile, which derives
 * from vital signs). Each run is a fresh PHP process, so "cold" includes building the package
 * index and "peak" is the whole process's peak memory.
 *
 * Usage:
 *   php src/Component/Validation/tests/Benchmark/runtime-profile-load.php <package-dir> [options]
 *
 * Options:
 *   --fhir-version=R4                  Release to resolve for (default: read from package.json)
 *   --profile=<canonical URL>          Profile to resolve (default: core bp)
 *   --runs=5                           Number of fresh-process runs (default: 5)
 *
 * Output: median and spread (min-max) for cold index+resolve, warm re-resolve, and peak memory.
 */

use Ardenexal\FHIRTools\Component\Validation\Profile\PackageDirectoryStructureDefinitionRegistry;
use Ardenexal\FHIRTools\Component\Validation\Profile\RuntimeProfileResolver;

require dirname(__DIR__, 5) . '/vendor/autoload.php';

// Parsed by hand: getopt() stops at the first positional argument, which here comes first.
$options = [];
$dir     = null;
foreach (array_slice($argv, 1) as $arg) {
    if (preg_match('/^--([a-z-]+)(?:=(.*))?$/', $arg, $m) === 1) {
        $options[$m[1]] = $m[2] ?? true;
    } else {
        $dir ??= $arg;
    }
}
if ($dir === null) {
    fwrite(STDERR, "Usage: php runtime-profile-load.php <package-dir> [--fhir-version=R4] [--profile=URL] [--runs=5]\n");
    exit(2);
}

$profile     = is_string($options['profile'] ?? null) ? $options['profile'] : 'http://hl7.org/fhir/StructureDefinition/bp';
$fhirVersion = is_string($options['fhir-version'] ?? null) ? $options['fhir-version'] : null;

if (($options['single'] ?? false) === true) {
    // One measured run, reported as JSON to the parent process.
    $start    = hrtime(true);
    $loader   = new PackageDirectoryStructureDefinitionRegistry($dir, $fhirVersion);
    $resolver = new RuntimeProfileResolver($loader);
    $release  = $fhirVersion ?? (function() use ($loader, $profile): string {
        $releases = $loader->fhirVersionsFor($profile);

        return $releases[0] ?? 'R4';
    })();
    $resolution = $resolver->resolve($profile, $release);
    $cold       = (hrtime(true) - $start) / 1e6;

    $start = hrtime(true);
    $resolver->resolve($profile, $release);
    $warm = (hrtime(true) - $start) / 1e6;

    echo json_encode([
        'resolved' => $resolution->isResolved(),
        'message'  => $resolution->message,
        'chain'    => count($resolution->chain),
        'cold_ms'  => $cold,
        'warm_ms'  => $warm,
        'peak_mb'  => memory_get_peak_usage(true) / 1048576,
    ], JSON_THROW_ON_ERROR), "\n";
    exit(0);
}

$runs    = max(1, (int) ($options['runs'] ?? 5));
$results = [];
for ($i = 0; $i < $runs; ++$i) {
    $command = [PHP_BINARY, '-d', 'memory_limit=1G', __FILE__, $dir, '--single', '--profile=' . $profile];
    if ($fhirVersion !== null) {
        $command[] = '--fhir-version=' . $fhirVersion;
    }
    $output = shell_exec(implode(' ', array_map('escapeshellarg', $command)));
    $result = json_decode(is_string($output) ? $output : '', true);
    if (!is_array($result)) {
        fwrite(STDERR, "Run {$i} failed:\n" . (string) $output);
        exit(1);
    }
    if ($result['resolved'] !== true) {
        fwrite(STDERR, 'Profile did not resolve: ' . $result['message'] . "\n");
        exit(1);
    }
    $results[] = $result;
}

$summary = static function(string $key, int $decimals = 1) use ($results): string {
    $values = array_column($results, $key);
    sort($values);
    $median = $values[intdiv(count($values), 2)];
    if (count($values) % 2 === 0) {
        $median = ($median + $values[count($values) / 2 - 1]) / 2;
    }

    $f = '%.' . $decimals . 'f';

    return sprintf("median {$f}, spread {$f}-{$f}", $median, $values[0], $values[count($values) - 1]);
};

printf("profile %s, chain length %d, %d runs\n", $profile, $results[0]['chain'], $runs);
printf("cold index+resolve ms: %s\n", $summary('cold_ms'));
printf("warm re-resolve ms:    %s\n", $summary('warm_ms', 3));
printf("peak memory MB:        %s\n", $summary('peak_mb'));
