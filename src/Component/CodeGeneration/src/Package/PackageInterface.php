<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\CodeGeneration\Package;

/**
 * Interface for FHIR packages
 *
 * Represents a loaded FHIR package with access to its metadata,
 * structure definitions, value sets, and other resources.
 *
 * @author FHIR Tools
 *
 * @since 1.0.0
 */
interface PackageInterface
{
    /**
     * Get the package name
     *
     * @return string The package name
     */
    public function getName(): string;

    /**
     * Get the package version
     *
     * @return string The package version
     */
    public function getVersion(): string;

    /**
     * Get the FHIR version this package is for
     *
     * @return string The FHIR version
     */
    public function getFhirVersion(): string;

    /**
     * Get package metadata
     *
     * @return array<string, mixed> The package metadata
     */
    public function getMetadata(): array;

    /**
     * Get all structure definitions in the package
     *
     * @return array<string, array<string, mixed>> Structure definitions keyed by URL
     */
    public function getStructureDefinitions(): array;

    /**
     * Get a specific structure definition by URL
     *
     * @param string $url The structure definition URL
     *
     * @return array<string, mixed>|null The structure definition or null if not found
     */
    public function getStructureDefinition(string $url): ?array;

    /**
     * Get all value sets in the package
     *
     * @return array<string, array<string, mixed>> Value sets keyed by URL
     */
    public function getValueSets(): array;

    /**
     * Get a specific value set by URL
     *
     * @param string $url The value set URL
     *
     * @return array<string, mixed>|null The value set or null if not found
     */
    public function getValueSet(string $url): ?array;

    /**
     * Get all code systems in the package
     *
     * @return array<string, array<string, mixed>> Code systems keyed by URL
     */
    public function getCodeSystems(): array;

    /**
     * Get a specific code system by URL
     *
     * @param string $url The code system URL
     *
     * @return array<string, mixed>|null The code system or null if not found
     */
    public function getCodeSystem(string $url): ?array;

    /**
     * Get all resources of a specific type
     *
     * @param string $resourceType The resource type (e.g., 'StructureDefinition', 'ValueSet')
     *
     * @return array<string, array<string, mixed>> Resources keyed by URL
     */
    public function getResourcesByType(string $resourceType): array;

    /**
     * Check if the package contains a resource with the given URL
     *
     * @param string $url The resource URL
     *
     * @return bool True if the resource exists
     */
    public function hasResource(string $url): bool;

    /**
     * Get a resource by URL
     *
     * @param string $url The resource URL
     *
     * @return array<string, mixed>|null The resource or null if not found
     */
    public function getResource(string $url): ?array;
}
