<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Enum;

/**
 * ValueSet: AllergyIntoleranceSeverity
 * URL: http://hl7.org/fhir/ValueSet/reaction-event-severity
 * Version: 4.3.0
 * Description: Clinical assessment of the severity of a reaction event as a whole, potentially considering multiple different manifestations.
 */
enum AllergyIntoleranceSeverity: string
{
	/** Mild */
	case mild = 'mild';

	/** Moderate */
	case moderate = 'moderate';

	/** Severe */
	case severe = 'severe';
}
