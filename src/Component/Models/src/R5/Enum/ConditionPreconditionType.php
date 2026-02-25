<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Enum;

/**
 * ValueSet: Condition Precondition Type
 * URL: http://hl7.org/fhir/ValueSet/condition-precondition-type
 * Version: 5.0.0
 * Description: Kind of precondition for the condition.
 */
enum ConditionPreconditionType: string
{
	/** Sensitive */
	case sensitive = 'sensitive';

	/** Specific */
	case specific = 'specific';
}
