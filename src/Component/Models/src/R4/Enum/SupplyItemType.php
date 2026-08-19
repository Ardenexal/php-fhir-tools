<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Enum;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetSource;

/**
 * ValueSet: Supply Item Type
 * URL: http://hl7.org/fhir/ValueSet/supplydelivery-type
 * Version: 4.0.1
 * Description: This value sets refers to a specific supply item.
 */
#[FHIRValueSetSource(url: 'http://hl7.org/fhir/ValueSet/supplydelivery-type', version: '4.0.1')]
enum SupplyItemType: string
{
    /** Medication */
    case medication = 'medication';

    /** Device */
    case device = 'device';
}
