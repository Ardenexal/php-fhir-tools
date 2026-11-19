<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation\Tests\Integration\Oracle;

use Ardenexal\FHIRTools\Component\Serialization\FhirVersion;
use Ardenexal\FHIRTools\Component\Validation\FHIRValidationService;
use Ardenexal\FHIRTools\Component\Validation\Profile\CompositeStructureDefinitionRegistry;
use Ardenexal\FHIRTools\Component\Validation\Profile\InMemoryStructureDefinitionRegistry;
use Ardenexal\FHIRTools\Component\Validation\Profile\PackageDirectoryStructureDefinitionRegistry;
use Ardenexal\FHIRTools\Component\Validation\Profile\RuntimeProfileResolver;

/**
 * The StructureDefinitions fhir-test-cases supply beside their instances, as validation services.
 *
 * A manifest entry can hand the reference validator its own definitions: `supporting` and
 * `profiles` files, and the `source` of a `profile` phase. Most ship a differential only, and runtime
 * validation needs a snapshot, so `Fixtures/SuppliedProfiles/seed-supplied-profiles.php` wrote them
 * once with snapshots from the reference validator. This class reads that output.
 *
 * Each case gets a service whose runtime registry holds only its own definitions, ahead of the core
 * definitions they derive from. Two cases may supply different definitions under one URL, so the
 * definitions are never pooled across cases.
 */
final class SuppliedProfiles
{
    /** @var array<string, string> source path from the manifest => file under the release's fixture directory */
    private readonly array $sources;

    /** @var array<string, FHIRValidationService> services already built, by their definitions and extension switch */
    private array $services = [];

    /** @var array<string, array<string, mixed>|null> decoded fixture definitions, by source path */
    private array $definitions = [];

    private function __construct(
        private readonly string $directory,
        private readonly FhirVersion $version,
    ) {
        $decoded       = json_decode((string) file_get_contents($directory . '/sources.json'), true);
        $this->sources = is_array($decoded) ? array_filter($decoded, 'is_string') : [];
    }

    /**
     * The release's supplied definitions, or null when none were seeded for it.
     */
    public static function forVersion(FhirVersion $version): ?self
    {
        $directory = dirname(__DIR__, 2) . '/Fixtures/SuppliedProfiles/' . strtolower($version->value);

        return is_file($directory . '/sources.json') ? new self($directory, $version) : null;
    }

    /**
     * The canonical URL of the definition a source path supplies, or null when it has no fixture.
     */
    public function urlOf(string $sourcePath): ?string
    {
        $url = $this->definition($sourcePath)['url'] ?? null;

        return is_string($url) ? $url : null;
    }

    /**
     * The StructureDefinitions a manifest entry supplies, as source paths in the order it lists them.
     *
     * The base run gets the case's `supporting` and `profiles`; its `profile` phase also gets the
     * phase's `source` and `supporting`, as the reference validator did.
     *
     * @param array<string, mixed> $case one manifest `test-cases` entry
     *
     * @return list<string>
     */
    public static function sourcesOf(array $case, bool $profilePhase = true): array
    {
        $profile = $profilePhase && is_array($case['profile'] ?? null) ? $case['profile'] : [];
        $paths   = [
            ...(is_array($case['supporting'] ?? null) ? $case['supporting'] : []),
            ...(is_array($case['profiles'] ?? null) ? $case['profiles'] : []),
            ...(is_string($profile['source'] ?? null) ? [$profile['source']] : []),
            ...(is_array($profile['supporting'] ?? null) ? $profile['supporting'] : []),
        ];

        return array_values(array_unique(array_filter($paths, 'is_string')));
    }

    /**
     * A validation service whose runtime profiles include these supplied definitions.
     *
     * Paths with no fixture (not a StructureDefinition, or one the reference validator could not
     * snapshot) are left out; a profile among them is then reported as not checked, as any unknown
     * profile is.
     *
     * @param list<string> $sourcePaths
     */
    public function serviceFor(array $sourcePaths, bool $resolveExtensions = true): FHIRValidationService
    {
        $definitions = [];
        foreach ($sourcePaths as $path) {
            $definition = $this->definition($path);
            if ($definition !== null) {
                $definitions[$path] = $definition;
            }
        }
        ksort($definitions);

        $key = implode("\n", array_keys($definitions)) . ($resolveExtensions ? '' : "\n-extensions");
        if (isset($this->services[$key])) {
            return $this->services[$key];
        }

        // Registered under the case's release: some supplied definitions do not state a fhirVersion
        $supplied = new InMemoryStructureDefinitionRegistry();
        foreach ($definitions as $definition) {
            $supplied->add($definition, $this->version->value);
        }

        return $this->services[$key] = OracleValidationServiceFactory::create(
            $this->version,
            $resolveExtensions,
            new RuntimeProfileResolver(new CompositeStructureDefinitionRegistry([
                $supplied,
                new PackageDirectoryStructureDefinitionRegistry($this->directory . '/core', $this->version->value),
                CoreStructureDefinitionPackages::registry($this->version),
            ])),
        );
    }

    /**
     * @return array<string, mixed>|null
     */
    private function definition(string $sourcePath): ?array
    {
        if (array_key_exists($sourcePath, $this->definitions)) {
            return $this->definitions[$sourcePath];
        }

        $file    = $this->sources[$sourcePath] ?? null;
        $decoded = $file === null ? null : json_decode((string) file_get_contents($this->directory . '/' . $file), true);

        /** @var array<string, mixed>|null $definition */
        $definition = is_array($decoded) ? $decoded : null;

        return $this->definitions[$sourcePath] = $definition;
    }
}
