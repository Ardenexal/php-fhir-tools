<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Enum;

/**
 * ValueSet: Action Precheck Behavior
 * URL: http://hl7.org/fhir/ValueSet/action-precheck-behavior
 * Version: 5.0.0
 * Description: Defines selection frequency behavior for an action or group.
 */
enum ActionPrecheckBehavior: string
{
	/** Yes */
	case yes = 'yes';

	/** No */
	case no = 'no';
}
