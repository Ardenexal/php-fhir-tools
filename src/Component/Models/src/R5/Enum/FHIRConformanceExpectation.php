<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Enum;

/**
 * ValueSet: Conformance Expectation
 * URL: http://hl7.org/fhir/ValueSet/conformance-expectation
 * Version: 5.0.0
 * Description: Description Needed Here
 */
enum FHIRConformanceExpectation: string
{
    /** SHALL */
    case shall = 'SHALL';

    /** SHOULD */
    case should = 'SHOULD';

    /** MAY */
    case may = 'MAY';

    /** SHOULD-NOT */
    case shouldnot = 'SHOULD-NOT';
}
