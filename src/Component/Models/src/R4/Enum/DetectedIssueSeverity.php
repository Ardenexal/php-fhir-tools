<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Enum;

/**
 * ValueSet: DetectedIssueSeverity
 * URL: http://hl7.org/fhir/ValueSet/detectedissue-severity
 * Version: 4.0.1
 * Description: Indicates the potential degree of impact of the identified issue on the patient.
 */
enum DetectedIssueSeverity: string
{
    /** High */
    case high = 'high';

    /** Moderate */
    case moderate = 'moderate';

    /** Low */
    case low = 'low';
}
