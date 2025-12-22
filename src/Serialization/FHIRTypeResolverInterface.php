<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Serialization;

/**
 * Interface for resolving FHIR types from serialized data using discriminator maps
 * and other type resolution strategies for polymorphic FHIR elements.
 *
 * @author Kiro AI Assistant
 */
interface FHIRTypeResolverInterface
{
    /**
     * Resolves the concrete type from the given data and context.
     *
     * @param array<string, mixed> $data    The serialized data to analyze
     * @param array<string, mixed> $context Additional context for type resolution
     *
     * @return string|null The resolved type class name, or null if type cannot be resolved
     */
    public function resolveType(array $data, array $context = []): ?string;

    /**
     * Gets the discriminator property name used for type resolution.
     *
     * @return string The property name that contains type information
     */
    public function getDiscriminatorProperty(): string;

    /**
     * Gets the mapping from discriminator values to concrete types.
     *
     * @return array<string, string> Mapping from discriminator value to class name
     */
    public function getTypeMapping(): array;

    /**
     * Resolves the FHIR resource type from resource data.
     *
     * @param array<string, mixed> $data The resource data containing resourceType
     *
     * @return string|null The resolved resource class name, or null if not resolvable
     */
    public function resolveResourceType(array $data): ?string;

    /**
     * Resolves the type for FHIR choice elements (value[x] patterns).
     *
     * @param string               $propertyName The base property name (e.g., "value")
     * @param array<string, mixed> $data         The data containing the choice element
     *
     * @return string|null The resolved type for the choice element, or null if not found
     */
    public function resolveChoiceElementType(string $propertyName, array $data): ?string;

    /**
     * Resolves the target type for FHIR reference elements.
     *
     * @param array<string, mixed> $referenceData The reference data containing type information
     *
     * @return string|null The resolved reference target type, or null if not resolvable
     */
    public function resolveReferenceType(array $referenceData): ?string;

    /**
     * Resolves the concrete complex type from the given data and context.
     *
     * @param array<string, mixed> $data    The serialized complex type data to analyze
     * @param array<string, mixed> $context Additional context for type resolution
     *
     * @return string|null The resolved complex type class name, or null if type cannot be resolved
     */
    public function resolveComplexType(array $data, array $context = []): ?string;
}
