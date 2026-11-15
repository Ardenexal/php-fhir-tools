<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Enum;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetSource;

/**
 * ValueSet: DeviceMetricCalibrationType
 * URL: http://hl7.org/fhir/ValueSet/metric-calibration-type
 * Version: 4.3.0
 * Description: Describes the type of a metric calibration.
 */
#[FHIRValueSetSource(url: 'http://hl7.org/fhir/ValueSet/metric-calibration-type', version: '4.3.0')]
enum DeviceMetricCalibrationType: string
{
    /** Unspecified */
    case unspecified = 'unspecified';

    /** Offset */
    case offset = 'offset';

    /** Gain */
    case gain = 'gain';

    /** Two Point */
    case twopoint = 'two-point';
}
