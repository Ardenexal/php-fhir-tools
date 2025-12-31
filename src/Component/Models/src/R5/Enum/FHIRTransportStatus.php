<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Enum;

/**
 * ValueSet: Transport Status
 * URL: http://hl7.org/fhir/ValueSet/transport-status
 * Version: 5.0.0
 * Description: Status of the transport
 */
enum FHIRTransportStatus: string
{
	/** In Progress */
	case inprogress = 'in-progress';

	/** Completed */
	case completed = 'completed';

	/** Abandoned */
	case abandoned = 'abandoned';

	/** Cancelled */
	case cancelled = 'cancelled';

	/** Planned */
	case planned = 'planned';

	/** Entered In Error */
	case enteredinerror = 'entered-in-error';
}
