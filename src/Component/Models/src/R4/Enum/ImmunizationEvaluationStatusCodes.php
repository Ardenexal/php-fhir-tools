<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Enum;

/**
 * ValueSet: Immunization Evaluation Status Codes
 * URL: http://hl7.org/fhir/ValueSet/immunization-evaluation-status
 * Version: 4.0.1
 * Description: The value set to instantiate this attribute should be drawn from a terminologically robust code system that consists of or contains concepts to support describing the current status of the evaluation for vaccine administration event.
 */
enum ImmunizationEvaluationStatusCodes: string
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
