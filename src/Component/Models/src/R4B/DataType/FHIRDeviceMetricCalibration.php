<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

/**
 * @fhir-backbone-element DeviceMetric.calibration
 * @description Describes the calibrations that have been performed or that are required to be performed.
 */
class FHIRDeviceMetricCalibration extends \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRDeviceMetricCalibrationTypeType type unspecified | offset | gain | two-point */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRDeviceMetricCalibrationTypeType $type = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRDeviceMetricCalibrationStateType state not-calibrated | calibration-required | calibrated | unspecified */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRDeviceMetricCalibrationStateType $state = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRInstant time Describes the time last calibration has been performed */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRInstant $time = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
