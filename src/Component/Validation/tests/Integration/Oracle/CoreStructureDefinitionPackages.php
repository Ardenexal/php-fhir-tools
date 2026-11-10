<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation\Tests\Integration\Oracle;

use Ardenexal\FHIRTools\Component\Serialization\FhirVersion;
use Ardenexal\FHIRTools\Component\Validation\Profile\InMemoryStructureDefinitionRegistry;
use Ardenexal\FHIRTools\Component\Validation\Profile\PackageDirectoryStructureDefinitionRegistry;
use Ardenexal\FHIRTools\Component\Validation\Profile\StructureDefinitionRegistryInterface;

/**
 * Finds the core FHIR package of a release on this machine, for tests that resolve profiles at runtime.
 *
 * The project's package cache (`demo/var/cache/dev/.fhir/`, gitignored, filled by `fhir:generate`)
 * is used when it holds the release and the caller allows it. Otherwise R5 falls back to the core package that ships in
 * vendor with the FHIR test cases, unpacked once into the system temp directory. R4 and R4B have no
 * such fallback, so on a machine without the cache they have no core package.
 */
final class CoreStructureDefinitionPackages
{
    private const string ROOT = __DIR__ . '/../../../../../..';

    /** Core packages in the project's package cache, by release. */
    public const array CACHED = [
        'R4'  => 'demo/var/cache/dev/.fhir/hl7.fhir.r4.core_4.0.1',
        'R4B' => 'demo/var/cache/dev/.fhir/hl7.fhir.r4b.core_4.3.0',
        'R5'  => 'demo/var/cache/dev/.fhir/hl7.fhir.r5.core_5.0.0',
    ];

    private const string VENDOR_R5 = 'vendor/fhir/fhir-test-cases/r5/packages/hl7.fhir.r5.core.tgz';

    /** @var array<string, string|null> */
    private static array $directories = [];

    /**
     * The committed, trimmed core definitions the parity test reads, or null until they are seeded.
     *
     * They hold each generated profile's StructureDefinition and its ancestors, written by
     * `tests/Fixtures/Profile/seed-core-profiles.php`, so the parity test runs the same everywhere.
     */
    public static function parityFixtures(FhirVersion $version): ?string
    {
        $directory = dirname(__DIR__, 2) . '/Fixtures/Profile/core-' . strtolower($version->value);

        return is_file($directory . '/package.json') ? $directory : null;
    }

    /**
     * The release's core package directory, or null when this machine has none.
     *
     * @param bool $projectCache false ignores the project's package cache, so the answer is the same
     *                           on every machine with this vendor directory
     */
    public static function directory(FhirVersion $version, bool $projectCache = true): ?string
    {
        $key = $version->value . ($projectCache ? '+cache' : '');
        if (array_key_exists($key, self::$directories)) {
            return self::$directories[$key];
        }

        $cached = self::ROOT . '/' . self::CACHED[$version->value];
        if ($projectCache && is_dir($cached)) {
            return self::$directories[$key] = $cached;
        }

        $archive = self::ROOT . '/' . self::VENDOR_R5;
        if ($version !== FhirVersion::R5 || !is_file($archive)) {
            return self::$directories[$key] = null;
        }

        return self::$directories[$key] = self::extract($archive);
    }

    /**
     * A registry over the release's core package from vendor, or an empty one when vendor has none.
     *
     * The project cache is left out on purpose: the comparison harness pins counts, and they must not
     * depend on which packages a developer last pulled.
     */
    public static function registry(FhirVersion $version): StructureDefinitionRegistryInterface
    {
        $directory = self::directory($version, projectCache: false);

        return $directory === null
            ? new InMemoryStructureDefinitionRegistry()
            : new PackageDirectoryStructureDefinitionRegistry($directory, $version->value);
    }

    /**
     * Unpack a package's StructureDefinitions and manifests, once per archive version.
     */
    private static function extract(string $archive): string
    {
        $target = sprintf('%s/fhir-tools-core-%s', sys_get_temp_dir(), md5($archive . filesize($archive) . filemtime($archive)));
        if (is_file($target . '/package/package.json')) {
            return $target;
        }

        $phar  = new \PharData($archive);
        $files = [];
        foreach (new \RecursiveIteratorIterator($phar) as $entry) {
            if (!$entry instanceof \SplFileInfo) {
                continue;
            }
            $name = $entry->getFilename();
            if (str_starts_with($name, 'StructureDefinition-') || $name === 'package.json' || $name === '.index.json') {
                $files[] = 'package/' . $name;
            }
        }

        // Unpack beside the target and rename into place, so a parallel test process never reads a
        // half-written directory.
        $staging = $target . '.' . getmypid();
        $phar->extractTo($staging, $files, true);
        if (!@rename($staging, $target)) {
            self::removeDirectory($staging);
        }

        return $target;
    }

    private static function removeDirectory(string $directory): void
    {
        $entries = scandir($directory);
        foreach ($entries === false ? [] : $entries as $entry) {
            if ($entry === '.' || $entry === '..') {
                continue;
            }
            $path = $directory . '/' . $entry;
            is_dir($path) ? self::removeDirectory($path) : unlink($path);
        }
        rmdir($directory);
    }
}
