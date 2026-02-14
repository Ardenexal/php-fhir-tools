<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\DeviceMetric;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\DeviceMetricCalibrationStateType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\DeviceMetricCalibrationTypeType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\InstantPrimitive;

/**
 * @description Describes the calibrations that have been performed or that are required to be performed.
 */
#[FHIRBackboneElement(parentResource: 'DeviceMetric', elementPath: 'DeviceMetric.calibration', fhirVersion: 'R4')]
class DeviceMetricCalibration extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var DeviceMetricCalibrationTypeType|null type unspecified | offset | gain | two-point */
        public ?DeviceMetricCalibrationTypeType $type = null,
        /** @var DeviceMetricCalibrationStateType|null state not-calibrated | calibration-required | calibrated | unspecified */
        public ?DeviceMetricCalibrationStateType $state = null,
        /** @var InstantPrimitive|null time Describes the time last calibration has been performed */
        public ?InstantPrimitive $time = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
