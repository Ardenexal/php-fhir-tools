<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Enum;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetSource;

/**
 * ValueSet: QuantityComparator
 * URL: http://hl7.org/fhir/ValueSet/quantity-comparator
 * Version: 4.3.0
 * Description: How the Quantity should be understood and represented.
 */
#[FHIRValueSetSource(url: 'http://hl7.org/fhir/ValueSet/quantity-comparator', version: '4.3.0')]
enum QuantityComparator: string
{
    /** Less than */
    case lessthan = '<';

    /** Less or Equal to */
    case lessorequalto = '<=';

    /** Greater or Equal to */
    case greaterorequalto = '>=';

    /** Greater than */
    case greaterthan = '>';
}
