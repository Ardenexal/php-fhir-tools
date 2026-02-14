<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Enum;

/**
 * ValueSet: InvoicePriceComponentType
 * URL: http://hl7.org/fhir/ValueSet/invoice-priceComponentType
 * Version: 4.0.1
 * Description: Codes indicating the kind of the price component.
 */
enum InvoicePriceComponentType: string
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
