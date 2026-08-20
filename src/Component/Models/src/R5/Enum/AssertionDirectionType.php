<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Enum;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetSource;

/**
 * ValueSet: Assertion Direction Type
 * URL: http://hl7.org/fhir/ValueSet/assert-direction-codes
 * Version: 5.0.0
 * Description: The type of direction to use for assertion.
 */
#[FHIRValueSetSource(url: 'http://hl7.org/fhir/ValueSet/assert-direction-codes', version: '5.0.0')]
enum AssertionDirectionType: string
{
    /** response */
    case response = 'response';

    /** request */
    case request = 'request';
}
