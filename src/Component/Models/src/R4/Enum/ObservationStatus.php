<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Enum;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetSource;

/**
 * ValueSet: ObservationStatus
 * URL: http://hl7.org/fhir/ValueSet/observation-status
 * Version: 4.0.1
 * Description: Codes providing the status of an observation.
 */
#[FHIRValueSetSource(url: 'http://hl7.org/fhir/ValueSet/observation-status', version: '4.0.1')]
enum ObservationStatus: string
{
    /** Registered */
    case registered = 'registered';

    /** Preliminary */
    case preliminary = 'preliminary';

    /** Final */
    case final = 'final';

    /** Amended */
    case amended = 'amended';

    /** Corrected */
    case corrected = 'corrected';

    /** Cancelled */
    case cancelled = 'cancelled';

    /** Entered in Error */
    case enteredinerror = 'entered-in-error';

    /** Unknown */
    case unknown = 'unknown';
}
