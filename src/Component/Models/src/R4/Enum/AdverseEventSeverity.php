<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Enum;

/**
 * ValueSet: AdverseEventSeverity
 * URL: http://hl7.org/fhir/ValueSet/adverse-event-severity
 * Version: 4.0.1
 * Description: The severity of the adverse event itself, in direct relation to the subject.
 */
enum AdverseEventSeverity: string
{
    /** Mild */
    case mild = 'mild';

    /** Moderate */
    case moderate = 'moderate';

    /** Severe */
    case severe = 'severe';
}
