<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Enum;

/**
 * ValueSet: Medication Status Codes
 * URL: http://hl7.org/fhir/ValueSet/medication-status
 * Version: 5.0.0
 * Description: Medication Status Codes
 */
enum MedicationStatusCodes: string
{
	/** Active */
	case active = 'active';

	/** Inactive */
	case inactive = 'inactive';

	/** Entered in Error */
	case enteredinerror = 'entered-in-error';
}
