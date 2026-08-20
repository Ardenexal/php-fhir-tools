<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Enum;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetSource;

/**
 * ValueSet: Charge Item Status
 * URL: http://hl7.org/fhir/ValueSet/chargeitem-status
 * Version: 5.0.0
 * Description: Codes identifying the lifecycle stage of a ChargeItem.
 */
#[FHIRValueSetSource(url: 'http://hl7.org/fhir/ValueSet/chargeitem-status', version: '5.0.0')]
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
