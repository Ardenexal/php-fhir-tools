<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Enum;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetSource;

/**
 * ValueSet: Claim Processing Codes
 * URL: http://hl7.org/fhir/ValueSet/claim-outcome
 * Version: 5.0.0
 * Description: This value set includes Claim Processing Outcome codes.
 */
#[FHIRValueSetSource(url: 'http://hl7.org/fhir/ValueSet/claim-outcome', version: '5.0.0')]
enum ClaimProcessingCodes: string
{
    /** Queued */
    case queued = 'queued';

    /** Processing Complete */
    case processingcomplete = 'complete';

    /** Error */
    case error = 'error';

    /** Partial Processing */
    case partialprocessing = 'partial';
}
