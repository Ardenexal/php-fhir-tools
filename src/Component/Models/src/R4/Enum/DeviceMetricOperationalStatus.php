<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Enum;

/**
 * ValueSet: DeviceMetricOperationalStatus
 * URL: http://hl7.org/fhir/ValueSet/metric-operational-status
 * Version: 4.0.1
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
