<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Enum;

/**
 * ValueSet: Composition Status
 * URL: http://hl7.org/fhir/ValueSet/composition-status
 * Version: 5.0.0
 * Description: The workflow/clinical status of the composition.
 */
enum CompositionStatus: string
{
	/** Registered */
	case registered = 'registered';

	/** Partial */
	case partial = 'partial';

	/** Final */
	case final = 'final';

	/** Amended */
	case amended = 'amended';

	/** Cancelled */
	case cancelled = 'cancelled';

	/** Entered in Error */
	case enteredinerror = 'entered-in-error';

	/** Deprecated */
	case deprecated = 'deprecated';

	/** Unknown */
	case unknown = 'unknown';
}
