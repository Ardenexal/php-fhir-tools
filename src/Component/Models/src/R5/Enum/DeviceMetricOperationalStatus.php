<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Enum;

/**
 * ValueSet: Device Metric Operational Status
 * URL: http://hl7.org/fhir/ValueSet/metric-operational-status
 * Version: 5.0.0
 * Description: Describes the operational status of the DeviceMetric.
 */
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
