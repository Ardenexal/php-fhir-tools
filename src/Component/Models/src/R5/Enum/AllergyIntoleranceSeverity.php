<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Enum;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetSource;

/**
 * ValueSet: Allergy Intolerance Severity
 * URL: http://hl7.org/fhir/ValueSet/reaction-event-severity
 * Version: 5.0.0
 * Description: Clinical assessment of the severity of a reaction event as a whole, potentially considering multiple different manifestations.
 */
#[FHIRValueSetSource(url: 'http://hl7.org/fhir/ValueSet/reaction-event-severity', version: '5.0.0')]
enum AllergyIntoleranceSeverity: string
{
    /** Mild */
    case mild = 'mild';

    /** Moderate */
    case moderate = 'moderate';

    /** Severe */
    case severe = 'severe';
}
