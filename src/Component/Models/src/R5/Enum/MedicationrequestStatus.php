<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Enum;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetSource;

/**
 * ValueSet: medicationrequest Status
 * URL: http://hl7.org/fhir/ValueSet/medicationrequest-status
 * Version: 5.0.0
 * Description: MedicationRequest Status Codes
 */
#[FHIRValueSetSource(url: 'http://hl7.org/fhir/ValueSet/medicationrequest-status', version: '5.0.0')]
enum MedicationrequestStatus: string
{
    /** Active */
    case active = 'active';

    /** On Hold */
    case onhold = 'on-hold';

    /** Ended */
    case ended = 'ended';

    /** Stopped */
    case stopped = 'stopped';

    /** Completed */
    case completed = 'completed';

    /** Cancelled */
    case cancelled = 'cancelled';

    /** Entered in Error */
    case enteredinerror = 'entered-in-error';

    /** Draft */
    case draft = 'draft';

    /** Unknown */
    case unknown = 'unknown';
}
