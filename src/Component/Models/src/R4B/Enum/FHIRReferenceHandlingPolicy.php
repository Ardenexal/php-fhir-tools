<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Enum;

/**
 * ValueSet: ReferenceHandlingPolicy
 * URL: http://hl7.org/fhir/ValueSet/reference-handling-policy
 * Version: 4.3.0
 * Description: A set of flags that defines how references are supported.
 */
enum FHIRReferenceHandlingPolicy: string
{
	/** Literal References */
	case literalreferences = 'literal';

	/** Logical References */
	case logicalreferences = 'logical';

	/** Resolves References */
	case resolvesreferences = 'resolves';

	/** Reference Integrity Enforced */
	case referenceintegrityenforced = 'enforced';

	/** Local References Only */
	case localreferencesonly = 'local';
}
