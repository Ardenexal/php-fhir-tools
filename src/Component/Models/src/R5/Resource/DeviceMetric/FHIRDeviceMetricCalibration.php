<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;

/**
 * @description Describes the calibrations that have been performed or that are required to be performed.
 */
#[FHIRBackboneElement(parentResource: 'DeviceMetric', elementPath: 'DeviceMetric.calibration', fhirVersion: 'R5')]
class FHIRDeviceMetricCalibration extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRDeviceMetricCalibrationTypeType|null type unspecified | offset | gain | two-point */
        public ?\FHIRDeviceMetricCalibrationTypeType $type = null,
        /** @var FHIRDeviceMetricCalibrationStateType|null state not-calibrated | calibration-required | calibrated | unspecified */
        public ?\FHIRDeviceMetricCalibrationStateType $state = null,
        /** @var FHIRInstant|null time Describes the time last calibration has been performed */
        public ?\FHIRInstant $time = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
