<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Enum;

/**
 * ValueSet: SupplyRequestStatus
 * URL: http://hl7.org/fhir/ValueSet/supplyrequest-status
 * Version: 4.0.1
 * Description: Status of the supply request.
 */
enum SupplyRequestStatus: string
{
    /** Draft */
    case draft = 'draft';

    /** Active */
    case active = 'active';

    /** Suspended */
    case suspended = 'suspended';

    /** Cancelled */
    case cancelled = 'cancelled';

    /** Completed */
    case completed = 'completed';

    /** Entered in Error */
    case enteredinerror = 'entered-in-error';

    /** Unknown */
    case unknown = 'unknown';
}
