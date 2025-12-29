<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Enum;

/**
 * ValueSet: Device Usage Status
 * URL: http://hl7.org/fhir/ValueSet/deviceusage-status
 * Version: 5.0.0
 * Description: A coded concept indicating the current status of the Device Usage.
 */
enum FHIRDeviceUsageStatus: string
{
	/** Active */
	case active = 'active';

	/** Completed */
	case completed = 'completed';

	/** Not done */
	case notdone = 'not-done';

	/** Entered in Error */
	case enteredinerror = 'entered-in-error';

	/** Intended */
	case intended = 'intended';

	/** Stopped */
	case stopped = 'stopped';

	/** On Hold */
	case onhold = 'on-hold';
}
