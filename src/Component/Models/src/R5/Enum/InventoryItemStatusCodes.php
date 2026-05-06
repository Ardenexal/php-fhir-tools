<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Enum;

/**
 * ValueSet: InventoryItem Status Codes
 * URL: http://hl7.org/fhir/ValueSet/inventoryitem-status
 * Version: 5.0.0
 * Description: InventoryItem Status Codes
 */
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
