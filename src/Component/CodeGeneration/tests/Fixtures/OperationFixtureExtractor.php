<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\CodeGeneration\Tests\Fixtures;

/**
 * Rebuilds the committed operation-manifest and type-index fixtures from the FHIR package cache.
 *
 * Four tests read these fixtures — the output-shape classifier, the class-namer collision check, the
 * per-operation fidelity check and the variant coverage gate — and until this existed **nothing in the
 * repository produced them**. They were extracted ad hoc, which quietly undercut the property those
 * tests claim: "written against the package contents, so it stays true when packages update". Nobody
 * could refresh them after a package bump without re-deriving the extraction by hand.
 *
 * The fixtures stay committed rather than read live. `demo/var/cache/dev/.fhir/` is gitignored and its
 * contents depend on which packages the developer last pulled, so a test reading it directly would
 * pass or fail for reasons unrelated to the code (M01 note N4). This class is the bridge: committed
 * data for the tests, a reproducible path back to the packages for whoever has to refresh it.
 *
 * Lives in `tests/Fixtures/` and not in `src/` on purpose — it is tooling for the test corpus, not
 * part of the shipped component, and mirrors `Validation`'s `seed-outcomes.php` precedent.
 *
 * @see OperationFixturesMatchPackagesTest which fails if a committed fixture drifts from the packages
 */
final class OperationFixtureExtractor
{
    /** Package directory names in the project cache, keyed by the version label used in filenames. */
    private const array PACKAGES = [
        'r4'  => 'hl7.fhir.r4.core_4.0.1',
        'r4b' => 'hl7.fhir.r4b.core_4.3.0',
        'r5'  => 'hl7.fhir.r5.core_5.0.0',
    ];

    /**
     * Fields carried verbatim per parameter, at every nesting depth.
     *
     * `searchType` is included and emitted even when null, because `opd-2`/`opd-4` make its presence
     * meaningful — a key that vanishes when unset is indistinguishable from one that was never read.
     */
    private const array PARAMETER_FIELDS = ['name', 'use', 'min', 'max', 'type', 'searchType'];

    /** Fields carried verbatim per StructureDefinition in the type index. */
    private const array TYPE_FIELDS = ['url', 'name', 'kind', 'derivation', 'baseDefinition', 'abstract'];

    public function __construct(
        private readonly string $cacheDirectory = __DIR__ . '/../../../../../demo/var/cache/dev/.fhir',
    ) {
    }

    /**
     * @return list<string> version labels this extractor can build, given what the cache holds
     */
    public function availableVersions(): array
    {
        $available = [];

        foreach (self::PACKAGES as $version => $package) {
            if (is_dir($this->packageDirectory($package))) {
                $available[] = $version;
            }
        }

        return $available;
    }

    /**
     * Every OperationDefinition in a package, keyed by canonical url.
     *
     * `kind` is carried rather than filtered so consumers can select `operation` themselves and a
     * `query` definition leaking into generated output stays detectable.
     *
     * @return array<string, array<string, mixed>>
     */
    public function buildOperationManifest(string $version): array
    {
        $manifest = [];

        foreach ($this->definitions($version, 'OperationDefinition') as $definition) {
            $url = $definition['url'] ?? null;

            if (!is_string($url)) {
                continue;
            }

            $entry = [
                'url'       => $url,
                'code'      => $definition['code']     ?? null,
                'kind'      => $definition['kind']     ?? null,
                'resource'  => $definition['resource'] ?? [],
                'instance'  => $definition['instance'] ?? false,
                'type'      => $definition['type']     ?? false,
                'system'    => $definition['system']   ?? false,
                'parameter' => $this->parameters($definition['parameter'] ?? null),
            ];

            ksort($entry);
            $manifest[$url] = $entry;
        }

        ksort($manifest);

        return $manifest;
    }

    /**
     * Every StructureDefinition in a package, keyed by canonical url.
     *
     * `kind` and `abstract` are the load-bearing fields: the output-shape classifier asks whether a
     * type is a resource, and a capitalisation heuristic got that wrong for `Meta` (a capitalised
     * complex-type). `derivation` matters because only `specialization` extends the type hierarchy.
     *
     * @return array<string, array<string, mixed>>
     */
    public function buildTypeIndex(string $version): array
    {
        $index = [];

        foreach ($this->definitions($version, 'StructureDefinition') as $definition) {
            $url = $definition['url'] ?? null;

            if (!is_string($url) || !$this->isCoreType($definition)) {
                continue;
            }

            $entry = [];

            // Absent fields are emitted as null rather than omitted, for the same reason `searchType`
            // is: `Resource` and `Element` are hierarchy roots with no `baseDefinition`, and a missing
            // key is indistinguishable from one that was never read.
            foreach (self::TYPE_FIELDS as $field) {
                $entry[$field] = $definition[$field] ?? null;
            }

            ksort($entry);
            $index[$url] = $entry;
        }

        ksort($index);

        return $index;
    }

    /**
     * Is this StructureDefinition a core FHIR type the index should carry?
     *
     * The index answers two questions for the generator — "is this type a resource?" and "what does it
     * specialize?" — so it carries only types that can answer them:
     *
     *  - `derivation: 'constraint'` is a **profile** of another type, not a type. Including profiles
     *    would put `us-core-patient` beside `Patient` and let a lookup resolve to the wrong one.
     *  - `kind: 'logical'` models (CDA and friends) are not part of the resource/datatype hierarchy.
     *
     * A definition with **no** `derivation` is kept: `Resource` and `Element` are roots and declare
     * none, and dropping them would break every `baseDefinition` chain at the top.
     *
     * @param array<string, mixed> $definition
     */
    private function isCoreType(array $definition): bool
    {
        if (($definition['kind'] ?? null) === 'logical') {
            return false;
        }

        $derivation = $definition['derivation'] ?? null;

        return $derivation === null || $derivation === 'specialization';
    }

    /**
     * Normalise a parameter tree, preserving published order and recursing through `part[]`.
     *
     * Order is preserved because `Parameters.parameter` is an ordered wire list and the fidelity check
     * asserts declaration order against it.
     *
     * @param mixed $parameters
     *
     * @return list<array<string, mixed>>
     */
    private function parameters(mixed $parameters): array
    {
        if (!is_array($parameters)) {
            return [];
        }

        $normalised = [];

        foreach ($parameters as $parameter) {
            if (!is_array($parameter)) {
                continue;
            }

            $entry = [];

            foreach (self::PARAMETER_FIELDS as $field) {
                $entry[$field] = $parameter[$field] ?? null;
            }

            $parts = $this->parameters($parameter['part'] ?? null);

            if ($parts !== []) {
                $entry['part'] = $parts;
            }

            // Sorted *after* `part` is added, so nesting sorts into alphabetical position rather than
            // trailing the scalar fields. Key order is not semantically meaningful, but the committed
            // fixtures carry it, and a fixture that differs only by key order produces a diff nobody
            // can read.
            ksort($entry);

            $normalised[] = $entry;
        }

        return $normalised;
    }

    /**
     * Decode every resource of one type from a package's directory.
     *
     * @return \Generator<array<string, mixed>>
     */
    private function definitions(string $version, string $resourceType): \Generator
    {
        $package = self::PACKAGES[$version] ?? throw new \InvalidArgumentException(sprintf('Unknown version "%s"; expected one of: %s', $version, implode(', ', array_keys(self::PACKAGES))));

        $files = glob($this->packageDirectory($package) . '/' . $resourceType . '-*.json');

        if ($files === false) {
            return;
        }

        sort($files);

        foreach ($files as $file) {
            $raw = file_get_contents($file);

            if ($raw === false) {
                continue;
            }

            /** @var mixed $decoded */
            $decoded = json_decode($raw, true);

            if (is_array($decoded) && ($decoded['resourceType'] ?? null) === $resourceType) {
                /** @var array<string, mixed> $decoded */
                yield $decoded;
            }
        }
    }

    private function packageDirectory(string $package): string
    {
        return $this->cacheDirectory . '/' . $package . '/package';
    }
}
