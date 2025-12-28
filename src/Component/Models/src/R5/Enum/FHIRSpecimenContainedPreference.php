<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Enum;

/**
 * ValueSet: SpecimenContainedPreference
 * URL: http://hl7.org/fhir/ValueSet/specimen-contained-preference
 * Version: 4.0.1
 * Description: Degree of preference of a type of conditioned specimen.
 */
enum FHIRSpecimenContainedPreference: string
{
	/** Preferred */
	case preferred = 'preferred';

	/** Alternate */
	case alternate = 'alternate';
}
