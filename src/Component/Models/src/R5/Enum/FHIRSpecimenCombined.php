<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Enum;

/**
 * ValueSet: Specimen Combined
 * URL: http://hl7.org/fhir/ValueSet/specimen-combined
 * Version: 5.0.0
 * Description: Codes providing the combined status of a specimen.
 */
enum FHIRSpecimenCombined: string
{
	/** Grouped */
	case grouped = 'grouped';

	/** Pooled */
	case pooled = 'pooled';
}
