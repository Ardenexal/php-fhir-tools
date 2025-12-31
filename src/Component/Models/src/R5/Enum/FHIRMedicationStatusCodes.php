<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Enum;

/**
 * ValueSet: Medication  status  codes
 * URL: http://hl7.org/fhir/ValueSet/medication-status
 * Version: 4.0.1
 * Description: Medication Status Codes
 */
enum FHIRMedicationStatusCodes: string
{
	/** Active */
	case active = 'active';

	/** Inactive */
	case inactive = 'inactive';

	/** Entered in Error */
	case enteredinerror = 'entered-in-error';
}
