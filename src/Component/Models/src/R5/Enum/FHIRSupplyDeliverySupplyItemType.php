<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Enum;

/**
 * ValueSet: Supply Delivery Supply Item Type
 * URL: http://hl7.org/fhir/ValueSet/supplydelivery-supplyitemtype
 * Version: 5.0.0
 * Description: This value sets refers to a specific supply item.
 */
enum FHIRSupplyDeliverySupplyItemType: string
{
    /** Medication */
    case medication = 'medication';

    /** Device */
    case device = 'device';

    /** Biologically Derived Product */
    case biologicallyderivedproduct = 'biologicallyderivedproduct';
}
