<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Enum;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetSource;

/**
 * ValueSet: AssertionDirectionType
 * URL: http://hl7.org/fhir/ValueSet/assert-direction-codes
 * Version: 4.3.0
 * Description: The type of direction to use for assertion.
 */
#[FHIRValueSetSource(url: 'http://hl7.org/fhir/ValueSet/assert-direction-codes', version: '4.3.0')]
enum AssertionDirectionType: string
{
    /** response */
    case response = 'response';

    /** request */
    case request = 'request';
}
