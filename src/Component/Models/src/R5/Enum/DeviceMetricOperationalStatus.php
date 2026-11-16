<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Enum;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetSource;

/**
 * ValueSet: Device Metric Operational Status
 * URL: http://hl7.org/fhir/ValueSet/metric-operational-status
 * Version: 5.0.0
 * Description: Describes the operational status of the DeviceMetric.
 */
#[FHIRValueSetSource(url: 'http://hl7.org/fhir/ValueSet/metric-operational-status', version: '5.0.0')]
enum DeviceMetricOperationalStatus: string
{
    /** On */
    case on = 'on';

    /** Off */
    case off = 'off';

    /** Standby */
    case standby = 'standby';

    /** Entered In Error */
    case enteredinerror = 'entered-in-error';
}
