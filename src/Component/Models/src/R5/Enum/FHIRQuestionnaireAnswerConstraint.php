<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Enum;

/**
 * ValueSet: Questionnaire answer constraints value set
 * URL: http://hl7.org/fhir/ValueSet/questionnaire-answer-constraint
 * Version: 5.0.0
 * Description: Codes that describe the types of constraints possible on a question item that has a list of permitted answers
 */
enum FHIRQuestionnaireAnswerConstraint: string
{
    /** Options only */
    case optionsonly = 'optionsOnly';

    /** Options or 'type' */
    case optionsortype = 'optionsOrType';

    /** Options or string */
    case optionsorstring = 'optionsOrString';
}
