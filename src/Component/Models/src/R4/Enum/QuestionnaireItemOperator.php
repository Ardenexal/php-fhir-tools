<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Enum;

/**
 * ValueSet: QuestionnaireItemOperator
 * URL: http://hl7.org/fhir/ValueSet/questionnaire-enable-operator
 * Version: 4.0.1
 * Description: The criteria by which a question is enabled.
 */
enum QuestionnaireItemOperator: string
{
    /** Exists */
    case exists = 'exists';

    /** Equals */
    case equals = '=';

    /** Not Equals */
    case notequals = '!=';

    /** Greater Than */
    case greaterthan = '>';

    /** Less Than */
    case lessthan = '<';

    /** Greater or Equals */
    case greaterorequals = '>=';

    /** Less or Equals */
    case lessorequals = '<=';
}
