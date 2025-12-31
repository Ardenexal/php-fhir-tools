<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Enum;

/**
 * ValueSet: Subscription Search Modifier
 * URL: http://hl7.org/fhir/ValueSet/subscription-search-modifier
 * Version: 4.3.0
 * Description: FHIR search modifiers allowed for use in Subscriptions and SubscriptionTopics.
 */
enum FHIRSubscriptionSearchModifier: string
{
	/** = */
	case equals = '=';

	/** Equal */
	case equal = 'eq';

	/** Not Equal */
	case notequal = 'ne';

	/** Greater Than */
	case greaterthan = 'gt';

	/** Less Than */
	case lessthan = 'lt';

	/** Greater Than or Equal */
	case greaterthanorequal = 'ge';

	/** Less Than or Equal */
	case lessthanorequal = 'le';

	/** Starts After */
	case startsafter = 'sa';

	/** Ends Before */
	case endsbefore = 'eb';

	/** Approximately */
	case approximately = 'ap';

	/** Above */
	case above = 'above';

	/** Below */
	case below = 'below';

	/** In */
	case in = 'in';

	/** Not In */
	case notin = 'not-in';

	/** Of Type */
	case oftype = 'of-type';
}
