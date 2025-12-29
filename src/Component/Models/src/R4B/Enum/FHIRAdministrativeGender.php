<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Enum;

/**
 * ValueSet: AdministrativeGender
 * URL: http://hl7.org/fhir/ValueSet/administrative-gender
 * Version: 4.3.0
 * Description: The gender of a person used for administrative purposes.
 */
enum FHIRAdministrativeGender: string
{
	/** Male */
	case male = 'male';

	/** Female */
	case female = 'female';

	/** Other */
	case other = 'other';

	/** Unknown */
	case unknown = 'unknown';
}
