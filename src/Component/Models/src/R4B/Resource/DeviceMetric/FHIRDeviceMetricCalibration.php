<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

/**
 * @description Describes the calibrations that have been performed or that are required to be performed.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'DeviceMetric', elementPath: 'DeviceMetric.calibration', fhirVersion: 'R4B')]
class FHIRDeviceMetricCalibration extends \Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRDeviceMetricCalibrationTypeType type unspecified | offset | gain | two-point */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRDeviceMetricCalibrationTypeType $type = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRDeviceMetricCalibrationStateType state not-calibrated | calibration-required | calibrated | unspecified */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRDeviceMetricCalibrationStateType $state = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRInstant time Describes the time last calibration has been performed */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRInstant $time = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
