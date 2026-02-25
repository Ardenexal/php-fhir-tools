<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Enum;

/**
 * ValueSet: Assertion Direction Type
 * URL: http://hl7.org/fhir/ValueSet/assert-direction-codes
 * Version: 5.0.0
 * Description: The type of direction to use for assertion.
 */
enum AssertionDirectionType: string
{
    /** response */
    case response = 'response';

    /** request */
    case request = 'request';
}
