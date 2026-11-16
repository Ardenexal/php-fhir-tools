<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Enum;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetSource;

/**
 * ValueSet: AssertionDirectionType
 * URL: http://hl7.org/fhir/ValueSet/assert-direction-codes
 * Version: 4.0.1
 * Description: The type of direction to use for assertion.
 */
#[FHIRValueSetSource(url: 'http://hl7.org/fhir/ValueSet/assert-direction-codes', version: '4.0.1')]
enum AssertionDirectionType: string
{
    /** response */
    case response = 'response';

    /** request */
    case request = 'request';
}
