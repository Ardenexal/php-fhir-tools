<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Enum;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetSource;

/**
 * ValueSet: Condition Questionnaire Purpose
 * URL: http://hl7.org/fhir/ValueSet/condition-questionnaire-purpose
 * Version: 5.0.0
 * Description: The use of a questionnaire.
 */
#[FHIRValueSetSource(url: 'http://hl7.org/fhir/ValueSet/condition-questionnaire-purpose', version: '5.0.0')]
enum ConditionQuestionnairePurpose: string
{
    /** Pre-admit */
    case preadmit = 'preadmit';

    /** Diff Diagnosis */
    case diffdiagnosis = 'diff-diagnosis';

    /** Outcome */
    case outcome = 'outcome';
}
