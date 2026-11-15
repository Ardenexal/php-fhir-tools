<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Enum;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetSource;

/**
 * ValueSet: Device Metric Category
 * URL: http://hl7.org/fhir/ValueSet/metric-category
 * Version: 5.0.0
 * Description: Describes the category of the metric.
 */
#[FHIRValueSetSource(url: 'http://hl7.org/fhir/ValueSet/metric-category', version: '5.0.0')]
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
