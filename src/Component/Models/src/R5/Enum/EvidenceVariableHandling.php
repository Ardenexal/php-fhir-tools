<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Enum;

/**
 * ValueSet: Evidence Variable Handling
 * URL: http://hl7.org/fhir/ValueSet/variable-handling
 * Version: 5.0.0
 * Description: The handling of the variable in statistical analysis for exposures or outcomes (E.g. Dichotomous, Continuous, Descriptive).
 */
enum EvidenceVariableHandling: string
{
	/** continuous variable */
	case continuousvariable = 'continuous';

	/** dichotomous variable */
	case dichotomousvariable = 'dichotomous';

	/** ordinal variable */
	case ordinalvariable = 'ordinal';

	/** polychotomous variable */
	case polychotomousvariable = 'polychotomous';
}
