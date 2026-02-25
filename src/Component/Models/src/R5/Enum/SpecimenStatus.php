<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Enum;

/**
 * ValueSet: Specimen Status
 * URL: http://hl7.org/fhir/ValueSet/specimen-status
 * Version: 5.0.0
 * Description: Codes providing the status/availability of a specimen.
 */
enum SpecimenStatus: string
{
	/** Available */
	case available = 'available';

	/** Unavailable */
	case unavailable = 'unavailable';

	/** Unsatisfactory */
	case unsatisfactory = 'unsatisfactory';

	/** Entered in Error */
	case enteredinerror = 'entered-in-error';
}
