<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Enum;

/**
 * ValueSet: Eligibility Outcome
 * URL: http://hl7.org/fhir/ValueSet/eligibility-outcome
 * Version: 5.0.0
 * Description: The outcome of the processing.
 */
enum FHIREligibilityOutcome: string
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
