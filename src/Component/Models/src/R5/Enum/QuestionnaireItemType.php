<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Enum;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetSource;

/**
 * ValueSet: Questionnaire Item Type
 * URL: http://hl7.org/fhir/ValueSet/item-type
 * Version: 5.0.0
 * Description: Distinguishes groups from questions and display text and indicates data type for questions.
 */
#[FHIRValueSetSource(url: 'http://hl7.org/fhir/ValueSet/item-type', version: '5.0.0')]
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

    /** Coding */
    case coding = 'coding';

    /** Attachment */
    case attachment = 'attachment';

    /** Reference */
    case reference = 'reference';

    /** Quantity */
    case quantity = 'quantity';
}
