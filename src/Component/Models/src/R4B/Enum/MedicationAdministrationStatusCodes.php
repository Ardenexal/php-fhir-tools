<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Enum;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetSource;

/**
 * ValueSet: MedicationAdministration Status Codes
 * URL: http://hl7.org/fhir/ValueSet/medication-admin-status
 * Version: 4.3.0
 * Description: MedicationAdministration Status Codes
 */
#[FHIRValueSetSource(url: 'http://hl7.org/fhir/ValueSet/medication-admin-status', version: '4.3.0')]
enum MedicationAdministrationStatusCodes: string
{
    /** In Progress */
    case inprogress = 'in-progress';

    /** Not Done */
    case notdone = 'not-done';

    /** On Hold */
    case onhold = 'on-hold';

    /** Completed */
    case completed = 'completed';

    /** Entered in Error */
    case enteredinerror = 'entered-in-error';

    /** Stopped */
    case stopped = 'stopped';

    /** Unknown */
    case unknown = 'unknown';
}
