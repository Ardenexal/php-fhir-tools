<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Enum;

/**
 * ValueSet: AssertionOperatorType
 * URL: http://hl7.org/fhir/ValueSet/assert-operator-codes
 * Version: 4.0.1
 * Description: The type of operator to use for assertion.
 */
enum AssertionOperatorType: string
{
    /** equals */
    case equals = 'equals';

    /** notEquals */
    case notequals = 'notEquals';

    /** in */
    case in = 'in';

    /** notIn */
    case notin = 'notIn';

    /** greaterThan */
    case greaterthan = 'greaterThan';

    /** lessThan */
    case lessthan = 'lessThan';

    /** empty */
    case empty = 'empty';

    /** notEmpty */
    case notempty = 'notEmpty';

    /** contains */
    case contains = 'contains';

    /** notContains */
    case notcontains = 'notContains';

    /** evaluate */
    case evaluate = 'eval';
}
