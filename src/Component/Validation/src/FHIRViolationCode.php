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

    /**
     * A profile asked for, directly or through `meta.profile`, was not checked: it is unknown,
     * its StructureDefinition cannot be used, it conflicts with another version checked on the same
     * resource, or there is no runtime profile resolver to check it with. Surfaced at WARNING
     * severity, so isValid() is unaffected; the reason is in the violation's `parameters['reason']`.
     */
    public const string PROFILE_NOT_CHECKED = 'fhir:profile-not-checked';
}
