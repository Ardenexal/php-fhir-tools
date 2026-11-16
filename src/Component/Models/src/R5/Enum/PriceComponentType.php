<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Enum;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetSource;

/**
 * ValueSet: Price Component Type
 * URL: http://hl7.org/fhir/ValueSet/price-component-type
 * Version: 5.0.0
 * Description: Codes indicating the kind of the price component.
 */
#[FHIRValueSetSource(url: 'http://hl7.org/fhir/ValueSet/price-component-type', version: '5.0.0')]
enum PriceComponentType: string
{
    /** base price */
    case baseprice = 'base';

    /** surcharge */
    case surcharge = 'surcharge';

    /** deduction */
    case deduction = 'deduction';

    /** discount */
    case discount = 'discount';

    /** tax */
    case tax = 'tax';

    /** informational */
    case informational = 'informational';
}
