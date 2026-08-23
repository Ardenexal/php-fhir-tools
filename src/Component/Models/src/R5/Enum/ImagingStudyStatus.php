<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Enum;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetSource;

/**
 * ValueSet: Imaging Study Status
 * URL: http://hl7.org/fhir/ValueSet/imagingstudy-status
 * Version: 5.0.0
 * Description: The status of the ImagingStudy.
 */
#[FHIRValueSetSource(url: 'http://hl7.org/fhir/ValueSet/imagingstudy-status', version: '5.0.0')]
enum ImagingStudyStatus: string
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
