<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Enum;

/**
 * ValueSet: Clinical Impression Status
 * URL: http://hl7.org/fhir/ValueSet/clinicalimpression-status
 * Version: 4.0.1
 * Description: Codes that reflect the current state of a clinical impression within its overall lifecycle.
 */
enum ClinicalImpressionStatus: string
{
    /** Preparation */
    case preparation = 'preparation';

    /** In Progress */
    case inprogress = 'in-progress';

    /** Not Done */
    case notdone = 'not-done';

    /** On Hold */
    case onhold = 'on-hold';

    /** Stopped */
    case stopped = 'stopped';

    /** Completed */
    case completed = 'completed';

    /** Entered in Error */
    case enteredinerror = 'entered-in-error';

    /** Unknown */
    case unknown = 'unknown';
}
