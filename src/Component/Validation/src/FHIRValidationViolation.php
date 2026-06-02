<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation;

/**
 * Immutable representation of a single FHIR validation violation.
 *
 * Severity is mapped from the violation code:
 *   'fhir:error'             → 'error'
 *   'fhir:warning'           → 'warning'
 *   'fhir:info'              → 'info'
 *   'fhir:eval-error'        → 'info' (invariant could not be evaluated; a tooling limitation, not non-conformance)
 *   'fhir:unchecked-binding' → 'info' (extensible/preferred binding skipped; no terminology client configured)
 *   any other code           → 'error' (covers built-in Symfony constraints like Count, NotBlank)
 */
final class FHIRValidationViolation
{
    /**
     * @param string               $severity        'error' | 'warning' | 'info'
     * @param string               $path            FHIR property path, e.g. 'identifier[0].system'
     * @param string               $message         Human-readable description (rendered, with parameters applied)
     * @param string               $constraintClass FQCN of the Symfony constraint that generated this violation
     * @param string|null          $profileGroup    Profile canonical URL if violation came from a profile group, else null
     * @param string|null          $invariantKey    FHIRPath invariant key (e.g. 'obs-7'), else null
     * @param array<string, mixed> $parameters      Raw message template parameters, e.g. ['{{ limit }}' => 1]
     * @param string|null          $code            Raw violation code (e.g. 'fhir:eval-error'), preserved so consumers
     *                                              can distinguish a tooling limitation from instance non-conformance;
     *                                              the underlying Symfony constraint code otherwise, which may be null
     */
    public function __construct(
        public readonly string $severity,
        public readonly string $path,
        public readonly string $message,
        public readonly string $constraintClass,
        public readonly ?string $profileGroup,
        public readonly ?string $invariantKey,
        public readonly array $parameters = [],
        public readonly ?string $code = null,
    ) {
    }
}
