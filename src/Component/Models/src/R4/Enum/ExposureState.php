<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Enum;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetSource;

/**
 * ValueSet: ExposureState
 * URL: http://hl7.org/fhir/ValueSet/exposure-state
 * Version: 4.0.1
 * Description: Whether the results by exposure is describing the results for the primary exposure of interest (exposure) or the alternative state (exposureAlternative).
 */
#[FHIRValueSetSource(url: 'http://hl7.org/fhir/ValueSet/exposure-state', version: '4.0.1')]
enum ExposureState: string
{
    /** Exposure */
    case exposure = 'exposure';

    /** Exposure Alternative */
    case exposurealternative = 'exposure-alternative';
}
