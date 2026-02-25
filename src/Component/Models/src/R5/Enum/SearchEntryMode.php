<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Enum;

/**
 * ValueSet: Search Entry Mode
 * URL: http://hl7.org/fhir/ValueSet/search-entry-mode
 * Version: 5.0.0
 * Description: Why an entry is in the result set - whether it's included as a match or because of an _include requirement, or to convey information or warning information about the search process.
 */
enum SearchEntryMode: string
{
	/** Match */
	case match = 'match';

	/** Include */
	case include = 'include';

	/** Outcome */
	case outcome = 'outcome';
}
