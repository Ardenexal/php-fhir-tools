<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Enum;

/**
 * ValueSet: Detected Issue Severity
 * URL: http://hl7.org/fhir/ValueSet/detectedissue-severity
 * Version: 5.0.0
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
