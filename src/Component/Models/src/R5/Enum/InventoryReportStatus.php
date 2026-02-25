<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Enum;

/**
 * ValueSet: Inventory Report Status
 * URL: http://hl7.org/fhir/ValueSet/inventoryreport-status
 * Version: 5.0.0
 * Description: The status of the InventoryReport.
 */
enum InventoryReportStatus: string
{
    /** Draft */
    case draft = 'draft';

    /** Requested */
    case requested = 'requested';

    /** Active */
    case active = 'active';

    /** Entered in Error */
    case enteredinerror = 'entered-in-error';
}
