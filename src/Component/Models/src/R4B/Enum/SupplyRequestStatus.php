<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Enum;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetSource;

/**
 * ValueSet: SupplyRequestStatus
 * URL: http://hl7.org/fhir/ValueSet/supplyrequest-status
 * Version: 4.3.0
 * Description: Status of the supply request.
 */
#[FHIRValueSetSource(url: 'http://hl7.org/fhir/ValueSet/supplyrequest-status', version: '4.3.0')]
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
