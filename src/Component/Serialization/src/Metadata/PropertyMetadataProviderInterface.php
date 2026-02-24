<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Serialization\Metadata;

/**
 * Provides pre-resolved property metadata for FHIR model classes.
 *
 * Implementations must be safe to call on every normalizer invocation — they are
 * expected to warm-cache metadata on first access and return from an in-process
 * map on subsequent calls.
 *
 * @author Ardenexal
 */
interface PropertyMetadataProviderInterface
{
    /**
     * Return the property metadata map for the given class.
     *
     * Keys are PHP property names (camelCase). Each value describes serialization
     * semantics for that property. Properties absent from the map have no FHIR
     * metadata and should be handled by a legacy fallback.
     *
     * @param class-string $className
     *
     * @return array<string, PropertyMetadata> Empty array if the class has no FHIR property metadata
     */
    public function getPropertyMetadata(string $className): array;
}
