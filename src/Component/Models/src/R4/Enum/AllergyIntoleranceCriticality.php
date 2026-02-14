<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Enum;

/**
 * ValueSet: AllergyIntoleranceCriticality
 * URL: http://hl7.org/fhir/ValueSet/allergy-intolerance-criticality
 * Version: 4.0.1
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
