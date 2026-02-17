<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Enum;

/**
 * ValueSet: ChargeItemStatus
 * URL: http://hl7.org/fhir/ValueSet/chargeitem-status
 * Version: 4.0.1
 * Description: Codes identifying the lifecycle stage of a ChargeItem.
 */
enum ChargeItemStatus: string
{
    /** Planned */
    case planned = 'planned';

    /** Billable */
    case billable = 'billable';

    /** Not billable */
    case notbillable = 'not-billable';

    /** Aborted */
    case aborted = 'aborted';

    /** Billed */
    case billed = 'billed';

    /** Entered in Error */
    case enteredinerror = 'entered-in-error';

    /** Unknown */
    case unknown = 'unknown';
}
