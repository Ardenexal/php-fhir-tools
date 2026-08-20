<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Enum;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetSource;

/**
 * ValueSet: Supply Delivery Supply Item Type
 * URL: http://hl7.org/fhir/ValueSet/supplydelivery-supplyitemtype
 * Version: 5.0.0
 * Description: This value sets refers to a specific supply item.
 */
#[FHIRValueSetSource(url: 'http://hl7.org/fhir/ValueSet/supplydelivery-supplyitemtype', version: '5.0.0')]
enum SupplyDeliverySupplyItemType: string
{
    /** Medication */
    case medication = 'medication';

    /** Device */
    case device = 'device';

    /** Biologically Derived Product */
    case biologicallyderivedproduct = 'biologicallyderivedproduct';
}
