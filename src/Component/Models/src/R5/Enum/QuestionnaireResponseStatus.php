<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Enum;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetSource;

/**
 * ValueSet: Questionnaire Response Status
 * URL: http://hl7.org/fhir/ValueSet/questionnaire-answers-status
 * Version: 5.0.0
 * Description: Lifecycle status of the questionnaire response.
 */
#[FHIRValueSetSource(url: 'http://hl7.org/fhir/ValueSet/questionnaire-answers-status', version: '5.0.0')]
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
