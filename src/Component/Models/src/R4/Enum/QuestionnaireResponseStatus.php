<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Enum;

/**
 * ValueSet: QuestionnaireResponseStatus
 * URL: http://hl7.org/fhir/ValueSet/questionnaire-answers-status
 * Version: 4.0.1
 * Description: Lifecycle status of the questionnaire response.
 */
enum QuestionnaireResponseStatus: string
{
    /** In Progress */
    case inprogress = 'in-progress';

    /** Completed */
    case completed = 'completed';

    /** Amended */
    case amended = 'amended';

    /** Entered in Error */
    case enteredinerror = 'entered-in-error';

    /** Stopped */
    case stopped = 'stopped';
}
