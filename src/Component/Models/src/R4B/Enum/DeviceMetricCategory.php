<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Enum;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetSource;

/**
 * ValueSet: DeviceMetricCategory
 * URL: http://hl7.org/fhir/ValueSet/metric-category
 * Version: 4.3.0
 * Description: Describes the category of the metric.
 */
#[FHIRValueSetSource(url: 'http://hl7.org/fhir/ValueSet/metric-category', version: '4.3.0')]
enum DeviceMetricCategory: string
{
    /** Measurement */
    case measurement = 'measurement';

    /** Setting */
    case setting = 'setting';

    /** Calculation */
    case calculation = 'calculation';

    /** Unspecified */
    case unspecified = 'unspecified';
}
