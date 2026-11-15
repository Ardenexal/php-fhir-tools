<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Enum;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetSource;

/**
 * ValueSet: Payment Outcome
 * URL: http://hl7.org/fhir/ValueSet/payment-outcome
 * Version: 5.0.0
 * Description: The outcome of the processing.
 */
#[FHIRValueSetSource(url: 'http://hl7.org/fhir/ValueSet/payment-outcome', version: '5.0.0')]
enum PaymentOutcome: string
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
