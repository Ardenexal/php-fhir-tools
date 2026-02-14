<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Enum;

/**
 * ValueSet: QuestionnaireItemType
 * URL: http://hl7.org/fhir/ValueSet/item-type
 * Version: 4.0.1
 * Description: Distinguishes groups from questions and display text and indicates data type for questions.
 */
enum QuestionnaireItemType: string
{
    /** Group */
    case group = 'group';

    /** Display */
    case display = 'display';

    /** Question */
    case question = 'question';
}
