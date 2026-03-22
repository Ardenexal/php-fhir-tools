<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Enum;

/**
 * ValueSet: MedicationStatement Status Codes
 * URL: http://hl7.org/fhir/ValueSet/medication-statement-status
 * Version: 4.3.0
 * Description: MedicationStatement Status Codes
 */
enum MedicationStatementStatusCodes: string
{
	/** Active */
	case active = 'active';

	/** Completed */
	case completed = 'completed';

	/** Entered in Error */
	case enteredinerror = 'entered-in-error';

	/** Intended */
	case intended = 'intended';

	/** Stopped */
	case stopped = 'stopped';

	/** On Hold */
	case onhold = 'on-hold';

	/** Unknown */
	case unknown = 'unknown';

	/** Not Taken */
	case nottaken = 'not-taken';
}
