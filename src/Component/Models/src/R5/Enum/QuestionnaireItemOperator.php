<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Enum;

/**
 * ValueSet: Questionnaire Item Operator
 * URL: http://hl7.org/fhir/ValueSet/questionnaire-enable-operator
 * Version: 5.0.0
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
