<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation;

final class FHIRViolationCode
{
    public const string ERROR = 'fhir:error';

    public const string WARNING = 'fhir:warning';

    public const string INFO = 'fhir:info';

    /**
     * A FHIRPath invariant could not be evaluated due to an engine limitation
     * (e.g. an unsupported function). This denotes tooling incompleteness, not
     * instance non-conformance, and is surfaced at INFO severity.
     */
    public const string EVAL_ERROR = 'fhir:eval-error';

    /**
     * An extensible/preferred binding check was skipped because no real terminology
     * client is configured (the client is null or a NullFHIRTerminologyClient).
     * This denotes a coverage gap, not instance non-conformance, and is surfaced
     * at INFO severity. Message overrides use the registry key
     * 'FHIRValueSetBindingUnchecked' (not a class name, unlike other keys).
     */
    public const string UNCHECKED_BINDING = 'fhir:unchecked-binding';
}
