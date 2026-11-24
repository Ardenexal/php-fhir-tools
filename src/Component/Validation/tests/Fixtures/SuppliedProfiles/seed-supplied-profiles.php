<?php

declare(strict_types=1);

/*
 * Writes the StructureDefinitions that fhir-test-cases supply beside their instances, with
 * snapshots, trimmed, into tests/Fixtures/SuppliedProfiles/<release>/.
 *
 * The comparison harness's --supplied-profiles mode validates the cases whose manifest entry hands
 * the reference validator its own StructureDefinitions (`supporting`, `profiles`, or a `profile`
 * phase). Runtime validation needs each definition's snapshot, and most of these ship a
 * differential only, so this script asks the HL7 reference validator to generate the snapshots once.
 * The library itself never generates snapshots.
 *
 * Output, per release:
 *   supplied/<name>.json   one trimmed definition per supplied source file
 *   sources.json           the manifest's source path => its file under supplied/
 *   core/                  a package directory holding the core definitions those profiles derive
 *                          from or name as data-type profiles: core profiles trimmed in full, and the
 *                          base type a chain ends at reduced to its identity fields
 *
 * Usage:
 *   php src/Component/Validation/tests/Fixtures/SuppliedProfiles/seed-supplied-profiles.php <R4|R4B|R5> <validator_cli.jar> <core-package-dir>
 *
 * <validator_cli.jar> is HL7's reference validator (https://github.com/hapifhir/org.hl7.fhir.core/releases);
 * it needs Java and reads its core packages from the FHIR package cache. <core-package-dir> is the
 * extracted core package of the release (hl7.fhir.r4.core 4.0.1, hl7.fhir.r4b.core 4.3.0 or
 * hl7.fhir.r5.core 5.0.0). Run it again after updating fhir/fhir-test-cases.
 */

use Ardenexal\FHIRTools\Component\Validation\Profile\PackageDirectoryStructureDefinitionRegistry;

require dirname(__DIR__, 6) . '/vendor/autoload.php';

/** Top-level fields kept on a definition that is compiled. */
const KEPT_FIELDS = [
    'resourceType', 'id', 'url', 'version', 'name', 'status', 'fhirVersion', 'kind', 'abstract',
    'context', 'contextInvariant', 'type', 'baseDefinition', 'derivation', 'extension', 'snapshot', 'differential',
];

/** Top-level fields kept on the base definition a chain ends at. */
const BASE_FIELDS = ['resourceType', 'id', 'url', 'version', 'name', 'status', 'fhirVersion', 'kind', 'abstract', 'type', 'baseDefinition', 'derivation'];

/** Element fields that are prose or mappings, never read by validation. */
const DROPPED_ELEMENT_FIELDS = [
    'short', 'definition', 'comment', 'requirements', 'alias', 'mapping', 'example', 'meaningWhenMissing',
    'isModifierReason', 'label', 'orderMeaning', 'code', 'base', 'isSummary', 'condition',
];

/** The manifest `version` values each release's cases carry, as ComparisonHarness::matchesVersion() reads them. */
const RELEASE_VERSIONS = ['R4' => ['4.0'], 'R4B' => ['4.3'], 'R5' => [null, '5.0', '5.0.0']];

/** The `-version` the reference validator takes for each release. */
const VALIDATOR_VERSIONS = ['R4' => '4.0', 'R4B' => '4.3', 'R5' => '5.0'];

[$script, $release, $validatorJar, $packageDir] = $argv + [null, null, null, null];
$repositoryRoot                                 = dirname(__DIR__, 6);
$casesDir                                       = $repositoryRoot . '/vendor/fhir/fhir-test-cases/validator';

if (!is_string($release) || !isset(RELEASE_VERSIONS[$release]) || !is_string($validatorJar) || !is_file($validatorJar) || !is_string($packageDir) || !is_dir($packageDir)) {
    fwrite(STDERR, "Usage: php {$script} <R4|R4B|R5> <validator_cli.jar> <core-package-dir>\n");
    exit(2);
}

/**
 * Elements without prose, keeping only the invariants a definition adds itself.
 *
 * Snapshot elements repeat every inherited invariant, which is most of a definition's size. Only
 * invariants this definition declares can become its rules, so a snapshot keeps those alone.
 *
 * @return list<array<string, mixed>>
 */
$trimElements = static function(mixed $elements, ?string $ownUrl): array {
    $trimmed = [];
    foreach (is_array($elements) ? $elements : [] as $element) {
        if (!is_array($element)) {
            continue;
        }
        $element = array_diff_key($element, array_flip(DROPPED_ELEMENT_FIELDS));
        if (is_array($element['constraint'] ?? null)) {
            $constraints = [];
            foreach ($element['constraint'] as $constraint) {
                if (!is_array($constraint) || ($ownUrl !== null && ($constraint['source'] ?? $ownUrl) !== $ownUrl)) {
                    continue;
                }
                unset($constraint['xpath'], $constraint['xpathUsage']);
                $constraints[] = $constraint;
            }
            $element['constraint'] = $constraints;
            if ($constraints === []) {
                unset($element['constraint']);
            }
        }
        if (is_array($element['binding'] ?? null)) {
            unset($element['binding']['description']);
        }
        $trimmed[] = $element;
    }

    return $trimmed;
};

/** @return array<string, mixed> */
$trimDefinition = static function(array $definition) use ($trimElements): array {
    $url     = (string) ($definition['url'] ?? '');
    $trimmed = array_intersect_key($definition, array_flip(KEPT_FIELDS));
    foreach (['snapshot', 'differential'] as $view) {
        if (is_array($trimmed[$view] ?? null)) {
            // The differential holds only what this definition states, so its invariants all stay
            $trimmed[$view] = ['element' => $trimElements($trimmed[$view]['element'] ?? [], $view === 'snapshot' ? $url : null)];
        }
    }

    return $trimmed;
};

// The StructureDefinition files this release's cases supply, by their manifest path
$manifest = json_decode((string) file_get_contents($casesDir . '/manifest.json'), true, flags: JSON_THROW_ON_ERROR);
$sources  = [];
foreach ($manifest['test-cases'] ?? [] as $case) {
    if (!is_array($case) || ($case['use-test'] ?? true) === false || !in_array($case['version'] ?? null, RELEASE_VERSIONS[$release], true)) {
        continue;
    }
    $profile = is_array($case['profile'] ?? null) ? $case['profile'] : [];
    foreach ([...($case['supporting'] ?? []), ...($case['supporting5'] ?? []), ...($case['profiles'] ?? []), ...(isset($profile['source']) ? [$profile['source']] : []), ...($profile['supporting'] ?? [])] as $path) {
        if (!is_string($path) || !is_file($casesDir . '/' . $path)) {
            continue;
        }
        $content      = (string) file_get_contents($casesDir . '/' . $path);
        $isDefinition = str_ends_with($path, '.json')
            ? (json_decode($content, true)['resourceType'] ?? null) === 'StructureDefinition'
            : preg_match('/<StructureDefinition[\s>]/', $content)   === 1;
        if ($isDefinition) {
            $sources[$path] = true;
        }
    }
}
// Kept in manifest order: the current fixtures were seeded in it
$sources = array_keys($sources);

// One batch for the release, so a profile that derives from another supplied profile finds it
$work = sys_get_temp_dir() . '/seed-supplied-' . strtolower($release) . '-' . getmypid();
mkdir($work, 0o777, true);
$staged = [];
foreach ($sources as $index => $path) {
    $name          = sprintf('%04d-%s', $index, basename($path));
    $staged[$path] = $name;
    copy($casesDir . '/' . $path, $work . '/' . $name);
}

// The reference validator works through its sources in order and stops at the first definition it
// cannot snapshot. Several cases supply deliberately broken profiles, so the batch is run again
// without each one that stopped it, until every source has a snapshot or has failed.
$pending = array_values($staged);
while ($pending !== []) {
    $command = sprintf(
        'java -Xmx3g -jar %s snapshot -version=%s -tx=n/a -outputSuffix=snapshot.json %s 2>&1',
        escapeshellarg($validatorJar),
        VALIDATOR_VERSIONS[$release],
        implode(' ', array_map(static fn (string $name): string => escapeshellarg($work . '/' . $name), $pending)),
    );
    $output = [];
    exec($command, $output, $exitCode);

    $remaining = array_values(array_filter($pending, static fn (string $name): bool => !is_file($work . '/' . $name . '.snapshot.json')));
    if ($exitCode === 0 || $remaining === []) {
        break;
    }

    // Java that cannot start, or has no package cache, fails every source alike; stop before
    // blaming each one in turn and wiping the committed fixtures below
    if (count($remaining) === count($staged)) {
        fwrite(STDERR, "The reference validator wrote no snapshot at all; fixtures left unchanged:\n" . implode("\n", array_slice($output, -10)) . "\n");
        exit(1);
    }

    // The first source still without a snapshot is the one that stopped the run
    $stopper = array_shift($remaining);
    $pending = $remaining;
    $errors  = array_values(preg_grep('/Exception|Error/', $output) ?: ['no snapshot']);
    fwrite(STDERR, "  {$stopper}: {$errors[0]}\n");
}

// Replace the previous set entirely, so a removed case does not leave its definition behind
$target = __DIR__ . '/' . strtolower($release);
foreach ([$target . '/supplied', $target . '/core'] as $directory) {
    foreach (is_dir($directory) ? (glob($directory . '/{,.}*.json', GLOB_BRACE) ?: []) : [] as $old) {
        unlink($old);
    }
    if (!is_dir($directory)) {
        mkdir($directory, 0o777, true);
    }
}

$written  = [];
$failed   = [];
$supplied = [];
foreach ($staged as $path => $name) {
    $generated  = $work . '/' . $name . '.snapshot.json';
    $definition = is_file($generated) ? json_decode((string) file_get_contents($generated), true) : null;
    if (!is_array($definition) || !is_array($definition['snapshot']['element'] ?? null)) {
        $failed[] = $path;
        continue;
    }
    $file = preg_replace('/[^A-Za-z0-9.-]+/', '-', $path) . (str_ends_with($path, '.json') ? '' : '.json');
    file_put_contents($target . '/supplied/' . $file, json_encode($trimDefinition($definition), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . "\n");
    $written[$path]                                = 'supplied/' . $file;
    $supplied[(string) ($definition['url'] ?? '')] = $definition;
}
ksort($written);

// The core definitions the supplied ones reach: each parent chain down to its base type, and the
// data-type profiles they name. Core constraints are kept in full (trimmed); the base type a chain
// ends at keeps its identity only, since resolution stops there and never compiles it.
$core  = new PackageDirectoryStructureDefinitionRegistry($packageDir, $release);
$found = [];
$queue = [];
foreach ($supplied as $definition) {
    $queue[] = $definition;
}
while ($queue !== []) {
    $definition = array_shift($queue);
    $references = [];
    $parent     = $definition['baseDefinition'] ?? null;
    if (is_string($parent) && $parent !== '') {
        $references[] = explode('|', $parent)[0];
    }
    foreach ($definition['differential']['element'] ?? [] as $element) {
        foreach (is_array($element) ? ($element['type'] ?? []) : [] as $type) {
            foreach (is_array($type) && is_array($type['profile'] ?? null) ? $type['profile'] : [] as $profile) {
                if (is_string($profile)) {
                    $references[] = explode('|', $profile)[0];
                }
            }
        }
    }
    foreach ($references as $url) {
        if (isset($supplied[$url]) || isset($found[$url])) {
            continue;
        }
        $coreDefinition = $core->find($url, $release);
        if ($coreDefinition === null) {
            continue;
        }
        $isConstraint = ($coreDefinition['derivation'] ?? null) === 'constraint';
        $found[$url]  = $isConstraint ? $trimDefinition($coreDefinition) : array_intersect_key($coreDefinition, array_flip(BASE_FIELDS));
        if ($isConstraint) {
            $queue[] = $coreDefinition;
        }
    }
}
ksort($found);

$index = [];
foreach ($found as $url => $definition) {
    $filename = 'StructureDefinition-' . preg_replace('/[^A-Za-z0-9.-]+/', '-', (string) ($definition['id'] ?? md5($url))) . '.json';
    file_put_contents($target . '/core/' . $filename, json_encode($definition, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . "\n");
    $index[] = array_filter([
        'filename'     => $filename,
        'resourceType' => 'StructureDefinition',
        'id'           => $definition['id'] ?? null,
        'url'          => $url,
        'version'      => $definition['version'] ?? null,
    ], static fn (mixed $value): bool => $value !== null);
}
$fhirVersions = ['R4' => '4.0.1', 'R4B' => '4.3.0', 'R5' => '5.0.0'];
file_put_contents($target . '/core/package.json', json_encode([
    'name'         => 'php-fhir-tools.supplied-core.' . strtolower($release),
    'version'      => '0.0.0',
    'description'  => 'Core StructureDefinitions the supplied test profiles derive from. Written by seed-supplied-profiles.php; do not edit.',
    'fhirVersions' => [$fhirVersions[$release]],
], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n");
file_put_contents($target . '/core/.index.json', json_encode(['index-version' => 1, 'files' => $index], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n");
file_put_contents($target . '/sources.json', json_encode($written, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n");

foreach (glob($work . '/*') ?: [] as $file) {
    unlink($file);
}
rmdir($work);

printf("Wrote %d of %d supplied definitions and %d core definitions to %s\n", count($written), count($sources), count($found), $target);
foreach ($failed as $path) {
    printf("  no snapshot generated: %s\n", $path);
}
