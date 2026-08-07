<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Enum;

/**
 * ValueSet: QuestionnaireItemType
 * URL: http://hl7.org/fhir/ValueSet/item-type
 * Version: 4.3.0
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

    /** Boolean */
    case boolean = 'boolean';

    /** Decimal */
    case decimal = 'decimal';

    /** Integer */
    case integer = 'integer';

    /** Date */
    case date = 'date';

    /** Date Time */
    case datetime = 'dateTime';

    /** Time */
    case time = 'time';

    /** String */
    case string = 'string';

    /** Text */
    case text = 'text';

    /** Url */
    case url = 'url';

    /** Choice */
    case choice = 'choice';

    /** Open Choice */
    case openchoice = 'open-choice';

    /** Attachment */
    case attachment = 'attachment';

    /** Reference */
    case reference = 'reference';

    /** Quantity */
    case quantity = 'quantity';
}
