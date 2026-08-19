<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Enum;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetSource;

/**
 * ValueSet: AllergyIntoleranceSeverity
 * URL: http://hl7.org/fhir/ValueSet/reaction-event-severity
 * Version: 4.0.1
 * Description: Clinical assessment of the severity of a reaction event as a whole, potentially considering multiple different manifestations.
 */
#[FHIRValueSetSource(url: 'http://hl7.org/fhir/ValueSet/reaction-event-severity', version: '4.0.1')]
enum AllergyIntoleranceSeverity: string
{
    /** Mild */
    case mild = 'mild';

    /** Moderate */
    case moderate = 'moderate';

    /** Severe */
    case severe = 'severe';
}
