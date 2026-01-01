<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Enum;

/**
 * ValueSet: ImagingStudyStatus
 * URL: http://hl7.org/fhir/ValueSet/imagingstudy-status
 * Version: 4.0.1
 * Description: The status of the ImagingStudy.
 */
enum FHIRImagingStudyStatus: string
{
    /** Registered */
    case registered = 'registered';

    /** Available */
    case available = 'available';

    /** Cancelled */
    case cancelled = 'cancelled';

    /** Entered in Error */
    case enteredinerror = 'entered-in-error';

    /** Unknown */
    case unknown = 'unknown';
}
