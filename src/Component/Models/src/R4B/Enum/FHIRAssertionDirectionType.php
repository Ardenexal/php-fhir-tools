<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Enum;

/**
 * ValueSet: AssertionDirectionType
 * URL: http://hl7.org/fhir/ValueSet/assert-direction-codes
 * Version: 4.0.1
 * Description: The type of direction to use for assertion.
 */
enum FHIRAssertionDirectionType: string
{
    /** response */
    case response = 'response';

    /** request */
    case request = 'request';
}
