<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Enum;

/**
 * ValueSet: Genomic Study Status
 * URL: http://hl7.org/fhir/ValueSet/genomicstudy-status
 * Version: 5.0.0
 * Description: The status of the GenomicStudy.
 */
enum FHIRGenomicStudyStatus: string
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
