<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Enum;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetSource;

/**
 * ValueSet: Supply Request Status
 * URL: http://hl7.org/fhir/ValueSet/supplyrequest-status
 * Version: 5.0.0
 * Description: Status of the supply request.
 */
#[FHIRValueSetSource(url: 'http://hl7.org/fhir/ValueSet/supplyrequest-status', version: '5.0.0')]
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
