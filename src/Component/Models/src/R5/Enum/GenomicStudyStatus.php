<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Enum;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetSource;

/**
 * ValueSet: Genomic Study Status
 * URL: http://hl7.org/fhir/ValueSet/genomicstudy-status
 * Version: 5.0.0
 * Description: The status of the GenomicStudy.
 */
#[FHIRValueSetSource(url: 'http://hl7.org/fhir/ValueSet/genomicstudy-status', version: '5.0.0')]
enum GenomicStudyStatus: string
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
