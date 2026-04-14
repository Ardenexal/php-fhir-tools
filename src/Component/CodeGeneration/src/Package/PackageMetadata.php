<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\CodeGeneration\Package;

/**
 * Represents comprehensive metadata for a FHIR package
 *
 * This class encapsulates all metadata associated with a FHIR package:
 *
 * - Basic package information (name, version, description)
 * - FHIR-specific metadata (supported versions, profiles)
 * - Dependency information with version constraints
 * - Package integrity and verification data
 * - Installation and caching metadata
 *
 * The metadata is typically loaded from package.json files in FHIR packages
 * and used throughout the package management system for dependency resolution,
 * version management, and integrity verification.
 *
 * @author FHIR Tools
 *
 * @since 1.0.0
 *
 * @package Ardenexal\FHIRTools\Component\CodeGeneration
 */
class PackageMetadata
{
    /**
     * Construct a new PackageMetadata instance
     *
     * @param string                $name           Package name
     * @param string                $version        Package version
     * @param array<string>         $fhirVersions   Supported FHIR versions
     * @param string                $url            Package download URL
     * @param string                $description    Package description
     * @param string                $author         Package author
     * @param string                $license        Package license
     * @param array<string, string> $dependencies   Package dependencies with version constraints
     * @param string                $title          Package title
     * @param string|null           $checksum       Package integrity checksum
     * @param array<string, mixed>  $additionalData Additional metadata
     */
    public function __construct(
        private string $name,
        private string $version,
        private array $fhirVersions,
        private string $url,
        private string $description,
        private string $author,
        private string $license,
        private array $dependencies,
        private string $title,
        private ?string $checksum = null,
        private array $additionalData = []
    ) {
    }

    /**
     * Create PackageMetadata from package.json data
     *
     * Raw FHIR version strings from package.json (e.g. "4.0.1") are normalized
     * to the named forms used throughout this toolkit ("R4", "R4B", "R5").
     *
     * @param array<string, mixed> $packageData Raw package.json data
     *
     * @return self The created PackageMetadata instance
     */
    public static function fromPackageData(array $packageData): self
    {
        $rawVersions = $packageData['fhirVersions'] ?? [];

        return new self(
            name: $packageData['name']       ?? '',
            version: $packageData['version'] ?? '',
            fhirVersions: array_values(array_unique(
                array_map(
                    static fn (string $v): string => self::normalizeFhirVersion($v),
                    array_filter($rawVersions, static fn (mixed $v): bool => is_string($v)),
                ),
            )),
            url: $packageData['url']               ?? '',
            description: $packageData['description'] ?? '',
            author: $packageData['author']           ?? '',
            license: $packageData['license']         ?? '',
            dependencies: $packageData['dependencies'] ?? [],
            title: $packageData['title']             ?? $packageData['name'] ?? '',
            checksum: $packageData['checksum']       ?? null,
            additionalData: array_diff_key($packageData, array_flip([
                'name', 'version', 'fhirVersions', 'url', 'description',
                'author', 'license', 'dependencies', 'title', 'checksum',
            ])),
        );
    }

    /**
     * Normalize a raw FHIR version string from package.json to the named form
     * expected by generators ("R4", "R4B", "R5").
     *
     * FHIR packages use numeric version strings that correspond to spec releases:
     *   4.0.x → R4   (e.g. "4.0.1", "4.0.0")
     *   4.3.x → R4B  (e.g. "4.3.0")
     *   5.0.x → R5   (e.g. "5.0.0", "5.0.0-snapshot3")
     *
     * Named forms that are already normalized ("R4", "R4B", "R5") pass through
     * unchanged, as do any unknown strings, to avoid breaking forward-compatibility
     * with future FHIR versions.
     */
    private static function normalizeFhirVersion(string $version): string
    {
        return match (true) {
            $version === 'R4' || $version === 'R4B' || $version === 'R5' => $version,
            str_starts_with($version, '4.0')                             => 'R4',
            str_starts_with($version, '4.3')                             => 'R4B',
            str_starts_with($version, '5.0')                             => 'R5',
            default                                                       => $version,
        };
    }

    /**
     * Get the package name
     *
     * @return string Package name
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Get the package version
     *
     * @return string Package version
     */
    public function getVersion(): string
    {
        return $this->version;
    }

    /**
     * Get supported FHIR versions
     *
     * @return array<string> Supported FHIR versions
     */
    public function getFhirVersions(): array
    {
        return $this->fhirVersions;
    }

    /**
     * Get the package download URL
     *
     * @return string Package URL
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * Get the package description
     *
     * @return string Package description
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * Get the package author
     *
     * @return string Package author
     */
    public function getAuthor(): string
    {
        return $this->author;
    }

    /**
     * Get the package license
     *
     * @return string Package license
     */
    public function getLicense(): string
    {
        return $this->license;
    }

    /**
     * Get package dependencies
     *
     * @return array<string, string> Dependencies with version constraints
     */
    public function getDependencies(): array
    {
        return $this->dependencies;
    }

    /**
     * Get the package title
     *
     * @return string Package title
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * Get the package checksum
     *
     * @return string|null Package checksum or null if not available
     */
    public function getChecksum(): ?string
    {
        return $this->checksum;
    }

    /**
     * Get additional metadata
     *
     * @return array<string, mixed> Additional metadata
     */
    public function getAdditionalData(): array
    {
        return $this->additionalData;
    }

    /**
     * Check if the package supports a specific FHIR version
     *
     * @param string $fhirVersion FHIR version to check
     *
     * @return bool True if the package supports the FHIR version
     */
    public function supportsFhirVersion(string $fhirVersion): bool
    {
        return in_array($fhirVersion, $this->fhirVersions, true);
    }

    /**
     * Check if the package has dependencies
     *
     * @return bool True if the package has dependencies
     */
    public function hasDependencies(): bool
    {
        return !empty($this->dependencies);
    }

    /**
     * Get a specific dependency constraint
     *
     * @param string $dependencyName Name of the dependency
     *
     * @return string|null Version constraint or null if dependency not found
     */
    public function getDependencyConstraint(string $dependencyName): ?string
    {
        return $this->dependencies[$dependencyName] ?? null;
    }

    /**
     * Check if the package has a specific dependency
     *
     * @param string $dependencyName Name of the dependency to check
     *
     * @return bool True if the package has the dependency
     */
    public function hasDependency(string $dependencyName): bool
    {
        return isset($this->dependencies[$dependencyName]);
    }

    /**
     * Get the package identifier (name@version)
     *
     * @return string Package identifier
     */
    public function getIdentifier(): string
    {
        return $this->name . '@' . $this->version;
    }

    /**
     * Convert to array representation
     *
     * @return array<string, mixed> Array representation of the metadata
     */
    public function toArray(): array
    {
        return [
            'name'           => $this->name,
            'version'        => $this->version,
            'fhirVersions'   => $this->fhirVersions,
            'url'            => $this->url,
            'description'    => $this->description,
            'author'         => $this->author,
            'license'        => $this->license,
            'dependencies'   => $this->dependencies,
            'title'          => $this->title,
            'checksum'       => $this->checksum,
            'additionalData' => $this->additionalData,
        ];
    }
}
