<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Enum;

/**
 * ValueSet: EventStatus
 * URL: http://hl7.org/fhir/ValueSet/event-status
 * Version: 4.3.0
 * Description: Codes identifying the lifecycle stage of an event.
 */
enum FHIREventStatus: string
{
	/** Preparation */
	case preparation = 'preparation';

	/** In Progress */
	case inprogress = 'in-progress';

	/** Not Done */
	case notdone = 'not-done';

	/** On Hold */
	case onhold = 'on-hold';

	/** Stopped */
	case stopped = 'stopped';

	/** Completed */
	case completed = 'completed';

	/** Entered in Error */
	case enteredinerror = 'entered-in-error';

	/** Unknown */
	case unknown = 'unknown';
}
