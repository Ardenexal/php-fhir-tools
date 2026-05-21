<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation;

/**
 * Null-object implementation used when no real terminology server is configured.
 * Always returns true so extensible/preferred bindings produce no violations.
 */
final class NullFHIRTerminologyClient implements FHIRTerminologyClientInterface
{
    public function validateCode(string $valueSetUrl, mixed $value): bool
    {
        return true;
    }
}
