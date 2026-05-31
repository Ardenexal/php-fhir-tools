<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation;

/**
 * Null-object implementation used when no real type hierarchy resolver is configured.
 * Always returns null, keeping bare-type extension contexts deferred (no violation emitted).
 */
final class NullFHIRTypeHierarchyResolver implements FHIRTypeHierarchyResolverInterface
{
    public function resolvePropertyType(object $element, string $propertyName): ?string
    {
        return null;
    }
}
