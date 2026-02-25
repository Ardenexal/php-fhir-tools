<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Enum;

/**
 * ValueSet: Observation Status
 * URL: http://hl7.org/fhir/ValueSet/observation-status
 * Version: 5.0.0
 * Description: Codes providing the status of an observation.
 */
enum ObservationStatus: string
{
	/** Registered */
	case registered = 'registered';

	/** Preliminary */
	case preliminary = 'preliminary';

	/** Final */
	case final = 'final';

	/** Amended */
	case amended = 'amended';

	/** Cancelled */
	case cancelled = 'cancelled';

	/** Entered in Error */
	case enteredinerror = 'entered-in-error';

	/** Unknown */
	case unknown = 'unknown';
}
