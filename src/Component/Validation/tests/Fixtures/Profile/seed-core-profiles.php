<?php

declare(strict_types=1);

/*
 * Writes the core StructureDefinitions the runtime profile parity test needs, trimmed, into
 * tests/Fixtures/Profile/core-<release>/.
 *
 * The parity test validates every generated profile class of a release against its own URL, once
 * through the class and once through the StructureDefinition resolved at runtime. That needs each
 * profile's StructureDefinition and every one it derives from. A core package is tens of megabytes
 * and cannot be fetched in every environment, so this script copies only those definitions, and
 * only their fields that validation reads: element prose (definitions, comments, mappings,
 * examples) and resource metadata (narrative, contacts, mappings) are dropped. The base resource
 * or data type a chain ends at keeps its identity fields only; resolution stops there and never
 * compiles it.
 *
 * Usage:
 *   php src/Component/Validation/tests/Fixtures/Profile/seed-core-profiles.php <R4|R4B|R5> <package-dir>
 *
 * <package-dir> is an extracted core package (hl7.fhir.r4.core 4.0.1, hl7.fhir.r4b.core 4.3.0 or
 * hl7.fhir.r5.core 5.0.0): the directory holding package.json, or the tarball root holding package/.
 * Run it again after regenerating models, so a new profile class gets its definitions.
 */

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRProfile;
use Ardenexal\FHIRTools\Component\Validation\Profile\PackageDirectoryStructureDefinitionLoader;

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

[$script, $release, $packageDir] = $argv + [null, null, null];
$repositoryRoot                  = dirname(__DIR__, 6);

$releases = ['R4' => '4.0.1', 'R4B' => '4.3.0', 'R5' => '5.0.0'];
if (!is_string($release) || !isset($releases[$release]) || !is_string($packageDir)) {
    fwrite(STDERR, "Usage: php {$script} <R4|R4B|R5> <package-dir>\n");
    exit(2);
}

$loader = new PackageDirectoryStructureDefinitionLoader($packageDir, $release);
$target = __DIR__ . '/core-' . strtolower($release);

/*
 * Snapshot elements repeat every inherited invariant (ele-1 on each element, the base resource's
 * own), which is most of a definition's size. Only invariants this profile adds can become its
 * rules, and those are in its differential or carry its URL as their source, so the snapshot keeps
 * those alone.
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

// Every profile URL a generated class of this release declares.
$urls  = [];
$files = glob("{$repositoryRoot}/src/Component/Models/src/{$release}/Profile/*.php");
foreach ($files === false ? [] : $files as $file) {
    $class = "Ardenexal\\FHIRTools\\Component\\Models\\{$release}\\Profile\\" . basename($file, '.php');
    if (!class_exists($class)) {
        continue;
    }
    foreach ((new ReflectionClass($class))->getAttributes(FHIRProfile::class) as $attribute) {
        $urls[] = $attribute->newInstance()->profileUrl;
    }
}

// Each profile and its ancestors, trimmed; the definition a chain ends at, reduced to its identity.
$written = [];
$missing = [];
foreach (array_unique($urls) as $url) {
    $current = $loader->find($release, $url);
    if ($current === null) {
        $missing[] = $url;
        continue;
    }
    while ($current !== null) {
        $currentUrl = (string) ($current['url'] ?? '');
        if (isset($written[$currentUrl])) {
            break;
        }
        $isConstraint = ($current['derivation'] ?? null) === 'constraint';
        if ($isConstraint) {
            $definition = array_intersect_key($current, array_flip(KEPT_FIELDS));
            foreach (['snapshot', 'differential'] as $view) {
                if (is_array($definition[$view] ?? null)) {
                    // The differential holds only what this profile states, so its invariants all stay.
                    $definition[$view] = ['element' => $trimElements($definition[$view]['element'] ?? [], $view === 'snapshot' ? $currentUrl : null)];
                }
            }
        } else {
            $definition = array_intersect_key($current, array_flip(BASE_FIELDS));
        }
        $written[$currentUrl] = $definition;

        $parent = $current['baseDefinition'] ?? null;
        if (!$isConstraint || !is_string($parent) || $parent === '') {
            break;
        }
        $pipe    = strpos($parent, '|');
        $current = $loader->find($release, $pipe === false ? $parent : substr($parent, 0, $pipe));
    }
}

ksort($written);

// Replace the previous set entirely, so a removed profile does not leave its definition behind.
if (is_dir($target)) {
    foreach (glob($target . '/*') ?: [] as $old) {
        unlink($old);
    }
    @unlink($target . '/.index.json');
} else {
    mkdir($target, 0o777, true);
}

$index = [];
foreach ($written as $url => $definition) {
    $filename = 'StructureDefinition-' . preg_replace('/[^A-Za-z0-9.-]+/', '-', (string) ($definition['id'] ?? md5($url))) . '.json';
    file_put_contents($target . '/' . $filename, json_encode($definition, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . "\n");
    $index[] = array_filter([
        'filename'     => $filename,
        'resourceType' => 'StructureDefinition',
        'id'           => $definition['id'] ?? null,
        'url'          => $url,
        'version'      => $definition['version'] ?? null,
    ], static fn (mixed $value): bool => $value !== null);
}

file_put_contents($target . '/package.json', json_encode([
    'name'         => 'php-fhir-tools.parity.' . strtolower($release),
    'version'      => '0.0.0',
    'description'  => 'Trimmed core StructureDefinitions for the runtime profile parity test. Written by seed-core-profiles.php; do not edit.',
    'fhirVersions' => [$releases[$release]],
], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n");
file_put_contents($target . '/.index.json', json_encode(['index-version' => 1, 'files' => $index], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n");

printf("Wrote %d StructureDefinitions for %d profile classes to %s\n", count($written), count(array_unique($urls)), $target);
foreach ($missing as $url) {
    printf("  not in the package: %s\n", $url);
}
