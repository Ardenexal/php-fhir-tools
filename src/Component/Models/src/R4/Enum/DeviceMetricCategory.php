<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Enum;

/**
 * ValueSet: DeviceMetricCategory
 * URL: http://hl7.org/fhir/ValueSet/metric-category
 * Version: 4.0.1
 * Description: Describes the category of the metric.
 */
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
