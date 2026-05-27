<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation;

/**
 * Contract for in-process FHIR reference resolution.
 *
 * A resolver receives a FHIR Reference object and returns the PHP object it points to
 * (already instantiated in the current request), or null when resolution is not possible.
 * The null object ({@see NullFHIRReferenceResolver}) always returns null, so target-profile
 * constraints are silently skipped when no real resolver is configured.
 *
 * Real implementations may inspect Reference::$reference for a local '#id' fragment and
 * walk a Bundle's contained resources, or consult a resource registry built during
 * deserialization.
 */
interface FHIRReferenceResolverInterface
{
    /**
     * Returns the resolved PHP object for the given reference, or null when the
     * reference cannot be resolved in the current context.
     */
    public function resolve(object $reference): ?object;
}
