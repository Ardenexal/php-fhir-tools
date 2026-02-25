<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Enum;

/**
 * ValueSet: medicationrequest Status
 * URL: http://hl7.org/fhir/ValueSet/medicationrequest-status
 * Version: 5.0.0
 * Description: MedicationRequest Status Codes
 */
enum MedicationrequestStatus: string
{
    /** Active */
    case active = 'active';

    /** On Hold */
    case onhold = 'on-hold';

    /** Ended */
    case ended = 'ended';

    /** Entered in Error */
    case enteredinerror = 'entered-in-error';

    /** Draft */
    case draft = 'draft';

    /** Unknown */
    case unknown = 'unknown';
}
