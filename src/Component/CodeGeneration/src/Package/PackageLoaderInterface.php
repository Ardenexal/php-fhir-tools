<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\CodeGeneration\Package;

use Ardenexal\FHIRTools\Component\CodeGeneration\Exception\PackageException;

/**
 * Interface for loading FHIR packages
 *
 * Defines the contract for loading and managing FHIR packages
 * from various sources (local files, remote URLs, package registries).
 *
 * @author FHIR Tools
 *
 * @since 1.0.0
 */
interface PackageLoaderInterface
{
    /**
     * Load a FHIR package by name and version
     *
     * @param string      $packageName The package name (e.g., 'hl7.fhir.r4.core')
     * @param string|null $version     The package version (null for latest)
     *
     * @return PackageInterface The loaded package
     *
     * @throws PackageException When package loading fails
     */
    public function loadPackage(string $packageName, ?string $version = null): PackageInterface;

    /**
     * Check if a package is available
     *
     * @param string      $packageName The package name
     * @param string|null $version     The package version (null for any version)
     *
     * @return bool True if the package is available
     */
    public function hasPackage(string $packageName, ?string $version = null): bool;

    /**
     * Get available versions for a package
     *
     * @param string $packageName The package name
     *
     * @return array<string> List of available versions
     *
     * @throws PackageException When package information cannot be retrieved
     */
    public function getAvailableVersions(string $packageName): array;

    /**
     * Clear package cache
     *
     * @param string|null $packageName Optional package name to clear specific package
     */
    public function clearCache(?string $packageName = null): void;
}
