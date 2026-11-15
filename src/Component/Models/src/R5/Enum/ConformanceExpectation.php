<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Enum;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetSource;

/**
 * ValueSet: Conformance Expectation
 * URL: http://hl7.org/fhir/ValueSet/conformance-expectation
 * Version: 5.0.0
 * Description: Description Needed Here
 */
#[FHIRValueSetSource(url: 'http://hl7.org/fhir/ValueSet/conformance-expectation', version: '5.0.0')]
enum ConformanceExpectation: string
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
