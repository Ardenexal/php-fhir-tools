<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Enum;

/**
 * ValueSet: Allergy Intolerance Criticality
 * URL: http://hl7.org/fhir/ValueSet/allergy-intolerance-criticality
 * Version: 5.0.0
 * Description: Estimate of the potential clinical harm, or seriousness, of a reaction to an identified substance.
 */
enum AllergyIntoleranceCriticality: string
{
	/** Low Risk */
	case lowrisk = 'low';

	/** High Risk */
	case highrisk = 'high';

	/** Unable to Assess Risk */
	case unabletoassessrisk = 'unable-to-assess';
}
