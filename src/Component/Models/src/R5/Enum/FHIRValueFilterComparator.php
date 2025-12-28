<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Enum;

/**
 * ValueSet: Value Filter Comparator
 * URL: http://hl7.org/fhir/ValueSet/value-filter-comparator
 * Version: 5.0.0
 * Description: The type of comparator operator to use
 */
enum FHIRValueFilterComparator: string
{
	/** Equals */
	case equals = 'eq';

	/** Not Equals */
	case notequals = 'ne';

	/** Greater Than */
	case greaterthan = 'gt';

	/** Less Than */
	case lessthan = 'lt';

	/** Greater or Equals */
	case greaterorequals = 'ge';

	/** Less of Equal */
	case lessofequal = 'le';

	/** Starts After */
	case startsafter = 'sa';

	/** Ends Before */
	case endsbefore = 'eb';

	/** Approximately */
	case approximately = 'ap';
}
