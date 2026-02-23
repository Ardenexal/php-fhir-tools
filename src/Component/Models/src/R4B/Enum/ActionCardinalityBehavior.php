<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Enum;

/**
 * ValueSet: ActionCardinalityBehavior
 * URL: http://hl7.org/fhir/ValueSet/action-cardinality-behavior
 * Version: 4.3.0
 * Description: Defines behavior for an action or a group for how many times that item may be repeated.
 */
enum ActionCardinalityBehavior: string
{
	/** Single */
	case single = 'single';

	/** Multiple */
	case multiple = 'multiple';
}
