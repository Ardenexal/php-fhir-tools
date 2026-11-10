<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation\Profile;

/**
 * Registry backed by an extracted FHIR package directory, such as `hl7.fhir.r4.core` or an IG.
 *
 * Nothing is read until the first lookup. The first lookup builds a URL-to-file index, and each
 * StructureDefinition is decoded only when it is first asked for, so pointing at a full core
 * package (hundreds of definitions, tens of megabytes) costs little until profiles are used.
 *
 * The index comes from the package's `.index.json` when it has one. Packages from
 * packages.fhir.org do; npm mirrors of the same packages often do not, and then the directory is
 * scanned instead: each `*.json` file whose first member names another resource type is skipped,
 * and the rest are decoded once to read their URL. Decoded files are not kept from the scan.
 *
 * A definition that cannot be read throws {@see UnreadableStructureDefinitionException} from the
 * lookup that needs it: a file `.index.json` lists that is missing or not JSON fails lookups of its
 * URL only. A file that names StructureDefinition but is not valid JSON fails the scan, since its URL
 * is unknown, and so every lookup; the failure is kept, so the directory is not scanned again.
 *
 * The FHIR release comes from the constructor, else from `package.json` (`fhirVersions`), else
 * from each StructureDefinition's own `fhirVersion` (which forces a scan).
 *
 * @author Ardenexal
 */
final class PackageDirectoryStructureDefinitionRegistry implements StructureDefinitionRegistryInterface
{
    /** Bytes read from the start of a file to find its resourceType without decoding it. */
    private const int HEAD_BYTES = 2048;

    private readonly string $directory;

    private readonly ?string $packageRelease;

    /**
     * File name by release label, then URL, then business version ('' when unversioned).
     * Null until the first lookup.
     *
     * @var array<string, array<string, array<string, string>>>|null
     */
    private ?array $index = null;

    /** @var array<string, array<string, mixed>> Decoded definitions by file name */
    private array $decoded = [];

    /** Why the scan failed, rethrown by every later lookup instead of scanning again. */
    private ?UnreadableStructureDefinitionException $scanFailure = null;

    /**
     * @param string      $directory   The package directory: the one holding `package.json`, or the
     *                                 extracted tarball root that holds a `package/` folder
     * @param string|null $fhirVersion Release label or spec version for every definition in the package;
     *                                 null reads it from `package.json`
     *
     * @throws \InvalidArgumentException When the directory does not exist, or $fhirVersion is not R4, R4B or R5
     */
    public function __construct(string $directory, ?string $fhirVersion = null)
    {
        $directory = rtrim($directory, '/\\');
        if (!is_dir($directory)) {
            throw new \InvalidArgumentException(sprintf('FHIR package directory "%s" does not exist.', $directory));
        }

        if (!is_file($directory . '/package.json') && is_file($directory . '/package/package.json')) {
            $directory .= '/package';
        }
        $this->directory = $directory;

        if ($fhirVersion !== null) {
            $release = FhirReleaseLabel::normalize($fhirVersion);
            if ($release === null) {
                throw new \InvalidArgumentException(sprintf('FHIR version "%s" is not R4, R4B or R5.', $fhirVersion));
            }
            $this->packageRelease = $release;
        } else {
            $this->packageRelease = $this->releaseFromManifest();
        }
    }

    public function find(string $url, string $fhirVersion, ?string $version = null): ?array
    {
        $release   = FhirReleaseLabel::normalize($fhirVersion);
        $byVersion = $release === null ? [] : ($this->index()[$release][$url] ?? []);
        if ($byVersion === []) {
            return null;
        }

        $file = $version !== null
            ? ($byVersion[$version] ?? null)
            : $byVersion[BusinessVersion::latest(array_keys($byVersion))];

        return $file === null ? null : $this->decode($file);
    }

    public function fhirVersionsFor(string $url): array
    {
        $releases = [];
        foreach ($this->index() as $release => $byUrl) {
            if (isset($byUrl[$url])) {
                $releases[] = $release;
            }
        }

        return $releases;
    }

    /**
     * Build the URL index on first use.
     *
     * @return array<string, array<string, array<string, string>>>
     *
     * @throws UnreadableStructureDefinitionException When the directory had to be scanned and holds a broken StructureDefinition
     */
    private function index(): array
    {
        if ($this->index !== null) {
            return $this->index;
        }
        if ($this->scanFailure !== null) {
            throw $this->scanFailure;
        }

        $index = $this->packageRelease === null ? null : $this->indexFromIndexFile($this->packageRelease);
        if ($index === null) {
            try {
                $index = $this->indexByScanning();
            } catch (UnreadableStructureDefinitionException $e) {
                throw $this->scanFailure = $e;
            }
        }

        return $this->index = $index;
    }

    /**
     * Build the index from `.index.json`.
     *
     * @return array<string, array<string, array<string, string>>>|null Null when the package has no usable `.index.json`
     */
    private function indexFromIndexFile(string $release): ?array
    {
        $path = $this->directory . '/.index.json';
        if (!is_file($path)) {
            return null;
        }

        $manifest = json_decode((string) file_get_contents($path), true);
        if (!is_array($manifest) || !is_array($manifest['files'] ?? null)) {
            return null;
        }

        $index = [];
        foreach ($manifest['files'] as $entry) {
            if (!is_array($entry) || ($entry['resourceType'] ?? null) !== 'StructureDefinition') {
                continue;
            }
            $url      = $entry['url']      ?? null;
            $filename = $entry['filename'] ?? null;
            if (!is_string($url) || $url === '' || !is_string($filename) || $filename === '') {
                continue;
            }
            $version                                                    = $entry['version'] ?? '';
            $index[$release][$url][is_string($version) ? $version : ''] = basename($filename);
        }

        return $index;
    }

    /**
     * Build the index by reading every JSON file in the directory (not recursively).
     *
     * @return array<string, array<string, array<string, string>>>
     *
     * @throws UnreadableStructureDefinitionException When a StructureDefinition file is not valid JSON
     */
    private function indexByScanning(): array
    {
        $index = [];
        $files = glob($this->directory . '/*.json');
        foreach ($files === false ? [] : $files as $path) {
            $filename     = basename($path);
            $declaredType = $filename === 'package.json' ? false : $this->headResourceType($path);
            if ($declaredType === false || ($declaredType !== null && $declaredType !== 'StructureDefinition')) {
                continue;
            }

            try {
                $resource = $this->decodeFile($path);
            } catch (UnreadableStructureDefinitionException $e) {
                // A file that announced itself as a StructureDefinition must be readable; any other
                // unreadable JSON file is not ours to judge.
                if ($declaredType === 'StructureDefinition') {
                    throw $e;
                }
                continue;
            }
            $url      = $resource['url'] ?? null;
            if (($resource['resourceType'] ?? null) !== 'StructureDefinition' || !is_string($url) || $url === '') {
                continue;
            }

            $declared = $resource['fhirVersion'] ?? null;
            $release  = $this->packageRelease    ?? (is_string($declared) ? FhirReleaseLabel::normalize($declared) : null);
            if ($release === null) {
                continue;
            }

            $version                                                    = $resource['version'] ?? '';
            $index[$release][$url][is_string($version) ? $version : ''] = $filename;
        }

        return $index;
    }

    /**
     * Read a file's resourceType from its opening bytes, without decoding the whole file.
     *
     * @return string|false|null The resourceType; null when it is not the first member; false when unreadable
     */
    private function headResourceType(string $path): string|false|null
    {
        $handle = @fopen($path, 'rb');
        if ($handle === false) {
            return false;
        }
        $head = (string) fread($handle, self::HEAD_BYTES);
        fclose($handle);

        // Only a resourceType that is the object's first member is trusted: one further in may
        // belong to a contained resource, since JSON member order is not significant.
        return preg_match('/^(?:\xEF\xBB\xBF)?\s*\{\s*"resourceType"\s*:\s*"([^"]*)"/', $head, $match) === 1 ? $match[1] : null;
    }

    /**
     * @return array<string, mixed>
     */
    private function decode(string $filename): array
    {
        return $this->decoded[$filename] ??= $this->decodeFile($this->directory . '/' . $filename);
    }

    /**
     * @return array<string, mixed>
     *
     * @throws UnreadableStructureDefinitionException When the file cannot be read or is not a JSON object
     */
    private function decodeFile(string $path): array
    {
        $json = is_file($path) ? @file_get_contents($path) : false;
        if ($json === false) {
            throw new UnreadableStructureDefinitionException(sprintf('Cannot read "%s".', $path));
        }

        try {
            $decoded = json_decode($json, true, 512, JSON_THROW_ON_ERROR);
        } catch (\JsonException $e) {
            throw new UnreadableStructureDefinitionException(sprintf('"%s" is not valid JSON: %s', $path, $e->getMessage()), 0, $e);
        }

        if (!is_array($decoded) || array_is_list($decoded) && $decoded !== []) {
            throw new UnreadableStructureDefinitionException(sprintf('"%s" does not hold a JSON object.', $path));
        }

        /** @var array<string, mixed> $decoded */
        return $decoded;
    }

    /**
     * Read the package's FHIR release from `package.json`, if it names exactly one we support.
     */
    private function releaseFromManifest(): ?string
    {
        $path = $this->directory . '/package.json';
        if (!is_file($path)) {
            return null;
        }

        $manifest = json_decode((string) file_get_contents($path), true);
        if (!is_array($manifest)) {
            return null;
        }

        $declared = $manifest['fhirVersions'] ?? $manifest['fhir-version-list'] ?? [];
        $releases = [];
        foreach (is_array($declared) ? $declared : [] as $version) {
            $release = is_string($version) ? FhirReleaseLabel::normalize($version) : null;
            if ($release !== null) {
                $releases[$release] = true;
            }
        }

        return count($releases) === 1 ? (string) array_key_first($releases) : null;
    }
}
