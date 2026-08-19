<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Enum;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetSource;

/**
 * ValueSet: InventoryItem Status Codes
 * URL: http://hl7.org/fhir/ValueSet/inventoryitem-status
 * Version: 5.0.0
 * Description: InventoryItem Status Codes
 */
#[FHIRValueSetSource(url: 'http://hl7.org/fhir/ValueSet/inventoryitem-status', version: '5.0.0')]
enum InventoryItemStatusCodes: string
{
    /** Active */
    case active = 'active';

    /** Inactive */
    case inactive = 'inactive';

    /** Entered in Error */
    case enteredinerror = 'entered-in-error';

    /** Unknown */
    case unknown = 'unknown';
}
