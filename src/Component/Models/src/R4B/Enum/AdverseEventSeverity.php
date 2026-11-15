<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Enum;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetSource;

/**
 * ValueSet: AdverseEventSeverity
 * URL: http://hl7.org/fhir/ValueSet/adverse-event-severity
 * Version: 4.3.0
 * Description: The severity of the adverse event itself, in direct relation to the subject.
 */
#[FHIRValueSetSource(url: 'http://hl7.org/fhir/ValueSet/adverse-event-severity', version: '4.3.0')]
enum AdverseEventSeverity: string
{
    /** Mild */
    case mild = 'mild';

    /** Moderate */
    case moderate = 'moderate';

    /** Severe */
    case severe = 'severe';
}
