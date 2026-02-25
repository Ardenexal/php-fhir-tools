<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Enum;

/**
 * ValueSet: Week Of Month
 * URL: http://hl7.org/fhir/ValueSet/week-of-month
 * Version: 5.0.0
 * Description: The set of weeks in a month.
 */
enum WeekOfMonth: string
{
	/** First */
	case first = 'first';

	/** Second */
	case second = 'second';

	/** Third */
	case third = 'third';

	/** Fourth */
	case fourth = 'fourth';

	/** Last */
	case last = 'last';
}
