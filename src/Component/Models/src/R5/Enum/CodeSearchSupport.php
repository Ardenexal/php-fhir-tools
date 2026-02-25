<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Enum;

/**
 * ValueSet: Code Search Support
 * URL: http://hl7.org/fhir/ValueSet/code-search-support
 * Version: 5.0.0
 * Description: The degree to which the server supports the code search parameter on ValueSet, if it is supported.
 */
enum CodeSearchSupport: string
{
	/** In Compose */
	case incompose = 'in-compose';

	/** In Expansion */
	case inexpansion = 'in-expansion';

	/** In Compose Or Expansion */
	case incomposeorexpansion = 'in-compose-or-expansion';
}
