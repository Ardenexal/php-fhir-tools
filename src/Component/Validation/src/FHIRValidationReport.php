<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation;

/**
 * Immutable collection of FHIR validation violations grouped by severity.
 *
 * isValid() returns true when there are no error-severity violations; warnings and info do not
 * affect validity per the FHIR specification.
 */
final class FHIRValidationReport
{
    /** @param list<FHIRValidationViolation> $violations */
    public function __construct(
        public readonly array $violations,
    ) {
    }

    public function isValid(): bool
    {
        return !$this->hasErrors();
    }

    public function hasErrors(): bool
    {
        return $this->errors() !== [];
    }

    public function hasWarnings(): bool
    {
        return $this->warnings() !== [];
    }

    /** @return list<FHIRValidationViolation> */
    public function errors(): array
    {
        return array_values(
            array_filter($this->violations, static fn (FHIRValidationViolation $v) => $v->severity === 'error'),
        );
    }

    /** @return list<FHIRValidationViolation> */
    public function warnings(): array
    {
        return array_values(
            array_filter($this->violations, static fn (FHIRValidationViolation $v) => $v->severity === 'warning'),
        );
    }

    /** @return list<FHIRValidationViolation> */
    public function info(): array
    {
        return array_values(
            array_filter($this->violations, static fn (FHIRValidationViolation $v) => $v->severity === 'info'),
        );
    }
}
