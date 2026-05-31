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
}
