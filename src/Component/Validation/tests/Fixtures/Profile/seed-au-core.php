<?php

declare(strict_types=1);

/*
 * Writes the AU Core 2.0.0 examples, and the StructureDefinitions that validating them resolves,
 * trimmed, into tests/Fixtures/Profile/au-core/.
 *
 * The AU Core regression test validates every example AU Core publishes against the profiles in its
 * meta.profile. The three packages that takes (AU Core, AU Base and the R4 core package) come to
 * about 70 MB, so this script keeps only the definitions validation actually looks up, found by
 * validating the examples against the full packages and recording each lookup, and only their
 * fields that validation reads. Element prose (definitions, comments, mappings, examples) and
 * resource metadata (narrative, contacts, mappings) are dropped, and so are the invariants a
 * snapshot repeats from the definitions it derives from. A base resource or data type keeps its
 * identity fields only; resolution stops there, and the compiler needs nothing more from it.
 *
 * All three packages are published under CC0-1.0, which allows redistributing them.
 *
 * Usage:
 *   php src/Component/Validation/tests/Fixtures/Profile/seed-au-core.php <au-core-dir> <au-base-dir> <r4-core-dir>
 *
 * Each directory is an extracted package: hl7.fhir.au.core 2.0.0, hl7.fhir.au.base 6.0.0 and
 * hl7.fhir.r4.core 4.0.1. Give the directory holding package.json, or the tarball root holding
 * package/. The script checks that the trimmed definitions give every example exactly the findings
 * the full packages give, and exits non-zero when they do not. The examples conform, so that check
 * catches a duplicated rule only when it reports on conforming data (an unchecked binding or a
 * warning), and never a dropped one. Nothing it drops holds a rule: rules
 * come from differentials and from a snapshot's own invariants, which are kept, and the inherited
 * invariants and base snapshots it removes are read only to avoid compiling a rule twice.
 */

use Ardenexal\FHIRTools\Component\Serialization\FHIRSerializationService;
use Ardenexal\FHIRTools\Component\Validation\FHIRValidationOptions;
use Ardenexal\FHIRTools\Component\Validation\FHIRValidationServiceBuilder;
use Ardenexal\FHIRTools\Component\Validation\FHIRValidationViolation;
use Ardenexal\FHIRTools\Component\Validation\Profile\CompositeStructureDefinitionRegistry;
use Ardenexal\FHIRTools\Component\Validation\Profile\PackageDirectoryStructureDefinitionRegistry;
use Ardenexal\FHIRTools\Component\Validation\Profile\StructureDefinitionRegistryInterface;

require dirname(__DIR__, 6) . '/vendor/autoload.php';

/** Top-level fields kept on a profile or extension definition. */
const KEPT_FIELDS = [
    'resourceType', 'id', 'url', 'version', 'name', 'status', 'fhirVersion', 'kind', 'abstract',
    'context', 'contextInvariant', 'type', 'baseDefinition', 'derivation', 'extension', 'snapshot', 'differential',
];

/** Top-level fields kept on the base definition a chain ends at. */
const BASE_FIELDS = ['resourceType', 'id', 'url', 'version', 'name', 'status', 'fhirVersion', 'kind', 'abstract', 'type', 'baseDefinition', 'derivation'];

/** Element fields validation never reads: prose, mappings and descriptive metadata such as base and isSummary. */
const DROPPED_ELEMENT_FIELDS = [
    'short', 'definition', 'comment', 'requirements', 'alias', 'mapping', 'example', 'meaningWhenMissing',
    'isModifierReason', 'label', 'orderMeaning', 'code', 'base', 'isSummary', 'condition',
];

[$script, $auCoreDir, $auBaseDir, $coreDir] = $argv + [null, null, null, null];
if (!is_string($auCoreDir) || !is_string($auBaseDir) || !is_string($coreDir)) {
    fwrite(STDERR, "Usage: php {$script} <au-core-dir> <au-base-dir> <r4-core-dir>\n");
    exit(2);
}

$packageRoot = static fn (string $dir): string => is_file("{$dir}/package.json") ? $dir : "{$dir}/package";
$target      = __DIR__ . '/au-core';

/**
 * Records each StructureDefinition a lookup finds, so the fixture holds exactly what validation reads.
 */
final class RecordingRegistry implements StructureDefinitionRegistryInterface
{
    /** @var array<string, array<string, mixed>> */
    public array $found = [];

    public function __construct(private readonly StructureDefinitionRegistryInterface $inner)
    {
    }

    public function find(string $url, string $fhirVersion, ?string $version = null): ?array
    {
        $definition = $this->inner->find($url, $fhirVersion, $version);
        if ($definition !== null) {
            $this->found[(string) ($definition['url'] ?? $url)] = $definition;
        }

        return $definition;
    }

    public function fhirVersionsFor(string $url): array
    {
        return $this->inner->fhirVersionsFor($url);
    }
}

/**
 * Every example's findings, one line per finding, keyed by example file name.
 *
 * @return array<string, list<string>>
 */
$findings = static function(StructureDefinitionRegistryInterface $registry, string $exampleDir): array {
    $service    = FHIRValidationServiceBuilder::create()->withStructureDefinitionRegistry($registry)->build();
    $serializer = FHIRSerializationService::createDefault();
    $result     = [];
    foreach (glob("{$exampleDir}/*.json") ?: [] as $file) {
        $report = $service->validateWithOptions(
            $serializer->deserialize((string) file_get_contents($file)),
            new FHIRValidationOptions(profilesFromMeta: true),
        );
        $result[basename($file)] = array_map(
            static fn (FHIRValidationViolation $v): string => "{$v->severity} {$v->path} {$v->message}",
            $report->violations,
        );
    }

    return $result;
};

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

// 1. Validate the examples against the full packages, recording every definition looked up.
$full = new RecordingRegistry(new CompositeStructureDefinitionRegistry([
    new PackageDirectoryStructureDefinitionRegistry($packageRoot($auCoreDir)),
    new PackageDirectoryStructureDefinitionRegistry($packageRoot($auBaseDir)),
    new PackageDirectoryStructureDefinitionRegistry($packageRoot($coreDir)),
]));
$exampleSource = $packageRoot($auCoreDir) . '/example';
$expected      = $findings($full, $exampleSource);

// 2. Replace the previous fixture entirely, so a definition no longer needed does not stay behind.
foreach (['definitions', 'examples'] as $sub) {
    if (is_dir("{$target}/{$sub}")) {
        foreach (glob("{$target}/{$sub}/{,.}*.json", GLOB_BRACE) ?: [] as $old) {
            unlink($old);
        }
    } else {
        mkdir("{$target}/{$sub}", 0o777, true);
    }
}

foreach (glob("{$exampleSource}/*.json") ?: [] as $file) {
    copy($file, "{$target}/examples/" . basename($file));
}

// 3. Write the recorded definitions, trimmed.
ksort($full->found);
$index = [];
foreach ($full->found as $url => $current) {
    if (($current['derivation'] ?? null) === 'constraint') {
        $definition = array_intersect_key($current, array_flip(KEPT_FIELDS));
        foreach (['snapshot', 'differential'] as $view) {
            if (is_array($definition[$view] ?? null)) {
                $definition[$view] = ['element' => $trimElements($definition[$view]['element'] ?? [], $view === 'snapshot' ? $url : null)];
            }
        }
    } else {
        $definition = array_intersect_key($current, array_flip(BASE_FIELDS));
    }

    $filename = 'StructureDefinition-' . preg_replace('/[^A-Za-z0-9.-]+/', '-', (string) ($definition['id'] ?? md5($url))) . '.json';
    file_put_contents("{$target}/definitions/{$filename}", json_encode($definition, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . "\n");
    $index[] = array_filter([
        'filename'     => $filename,
        'resourceType' => 'StructureDefinition',
        'id'           => $definition['id'] ?? null,
        'url'          => $url,
        'version'      => $definition['version'] ?? null,
    ], static fn (mixed $value): bool => $value !== null);
}

file_put_contents("{$target}/definitions/package.json", json_encode([
    'name'         => 'php-fhir-tools.au-core-regression',
    'version'      => '0.0.0',
    'description'  => 'Trimmed AU Core 2.0.0, AU Base 6.0.0 and R4 core StructureDefinitions (CC0-1.0) for the AU Core regression test. Written by seed-au-core.php; do not edit.',
    'fhirVersions' => ['4.0.1'],
], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n");
file_put_contents("{$target}/definitions/.index.json", json_encode(['index-version' => 1, 'files' => $index], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n");

// 4. The trimmed definitions must give every example the same findings as the full packages.
$actual = $findings(new PackageDirectoryStructureDefinitionRegistry("{$target}/definitions"), "{$target}/examples");
$diff   = 0;
foreach ($expected as $example => $lines) {
    if (($actual[$example] ?? null) !== $lines) {
        ++$diff;
        fwrite(STDERR, "Findings differ for {$example}\n  full:    " . implode("\n           ", $lines) . "\n  trimmed: " . implode("\n           ", $actual[$example] ?? []) . "\n");
    }
}

printf(
    "Wrote %d StructureDefinitions and %d examples to %s; %d examples with %d findings in all\n",
    count($index),
    count($expected),
    $target,
    count($expected),
    array_sum(array_map('count', $expected)),
);
exit($diff === 0 ? 0 : 1);
