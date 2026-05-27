<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation;

/**
 * Null-object implementation used when no real reference resolver is configured.
 * Always returns null so target-profile constraints produce no violations.
 */
final class NullFHIRReferenceResolver implements FHIRReferenceResolverInterface
{
    public function resolve(object $reference): ?object
    {
        return null;
    }
}
