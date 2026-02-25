<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Enum;

/**
 * ValueSet: Action Required Behavior
 * URL: http://hl7.org/fhir/ValueSet/action-required-behavior
 * Version: 5.0.0
 * Description: Defines expectations around whether an action or action group is required.
 */
enum ActionRequiredBehavior: string
{
	/** Must */
	case must = 'must';

	/** Could */
	case could = 'could';

	/** Must Unless Documented */
	case mustunlessdocumented = 'must-unless-documented';
}
