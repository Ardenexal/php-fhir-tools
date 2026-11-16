<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Enum;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetSource;

/**
 * ValueSet: Inventory Count Type
 * URL: http://hl7.org/fhir/ValueSet/inventoryreport-counttype
 * Version: 5.0.0
 * Description: The type of count.
 */
#[FHIRValueSetSource(url: 'http://hl7.org/fhir/ValueSet/inventoryreport-counttype', version: '5.0.0')]
enum InventoryCountType: string
{
    /** Snapshot */
    case snapshot = 'snapshot';

    /** Difference */
    case difference = 'difference';
}
