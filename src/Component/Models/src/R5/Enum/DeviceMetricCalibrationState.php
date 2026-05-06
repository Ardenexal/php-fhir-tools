<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Enum;

/**
 * ValueSet: Device Metric Calibration State
 * URL: http://hl7.org/fhir/ValueSet/metric-calibration-state
 * Version: 5.0.0
 * Description: Describes the state of a metric calibration.
 */
enum DeviceMetricCalibrationState: string
{
    /** Not Calibrated */
    case notcalibrated = 'not-calibrated';

    /** Calibration Required */
    case calibrationrequired = 'calibration-required';

    /** Calibrated */
    case calibrated = 'calibrated';

    /** Unspecified */
    case unspecified = 'unspecified';
}
