<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation;

/**
 * Null-object implementation used when no real type hierarchy resolver is configured.
 * Always returns null, keeping bare-type extension contexts deferred (no violation emitted).
 */
final class NullFHIRTypeHierarchyResolver implements FHIRTypeHierarchyResolverInterface
{
    /**
     * Returns null, deferring bare-type extension context matching.
     *
     * @param object $element      The FHIR element (unused in this null implementation)
     * @param string $propertyName The property name (unused in this null implementation)
     *
     * @return null Always returns null to defer type resolution
     */
    public function resolvePropertyType(object $element, string $propertyName): ?string
    {
        return null;
    }

    /**
     * Returns an empty array, deferring bare-type and foreign-root extension context matching.
     *
     * @param object $element The FHIR element (unused in this null implementation)
     *
     * @return list<string> Always returns an empty list to defer type hierarchy resolution
     */
    public function resolveTypeHierarchy(object $element): array
    {
        return [];
    }
}
