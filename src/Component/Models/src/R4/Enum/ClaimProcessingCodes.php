<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Enum;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetSource;

/**
 * ValueSet: Claim Processing Codes
 * URL: http://hl7.org/fhir/ValueSet/remittance-outcome
 * Version: 4.0.1
 * Description: This value set includes Claim Processing Outcome codes.
 */
#[FHIRValueSetSource(url: 'http://hl7.org/fhir/ValueSet/remittance-outcome', version: '4.0.1')]
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
