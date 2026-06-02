<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation;

/**
 * Null-object implementation used when no real terminology server is configured.
 *
 * FHIRValueSetBindingValidator treats this client the same as having no client at all:
 * extensible/preferred binding checks are skipped and each skip is surfaced as a
 * fhir:unchecked-binding INFO violation (issue #71). To suppress those, wire a real
 * client (e.g. HttpFHIRTerminologyClient) or filter the code at the application layer.
 */
final class NullFHIRTerminologyClient implements FHIRTerminologyClientInterface
{
    public function validateCode(string $valueSetUrl, mixed $value): bool
    {
        return true;
    }
}
