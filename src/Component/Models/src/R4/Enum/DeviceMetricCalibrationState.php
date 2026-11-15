<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Enum;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetSource;

/**
 * ValueSet: DeviceMetricCalibrationState
 * URL: http://hl7.org/fhir/ValueSet/metric-calibration-state
 * Version: 4.0.1
 * Description: Describes the state of a metric calibration.
 */
#[FHIRValueSetSource(url: 'http://hl7.org/fhir/ValueSet/metric-calibration-state', version: '4.0.1')]
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
