<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\DeviceMetric;

/**
 * @description Describes the calibrations that have been performed or that are required to be performed.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'DeviceMetric', elementPath: 'DeviceMetric.calibration', fhirVersion: 'R4')]
class DeviceMetricCalibration extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\DeviceMetricCalibrationTypeType type unspecified | offset | gain | two-point */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\DeviceMetricCalibrationTypeType $type = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\DeviceMetricCalibrationStateType state not-calibrated | calibration-required | calibrated | unspecified */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\DeviceMetricCalibrationStateType $state = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\InstantPrimitive time Describes the time last calibration has been performed */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\InstantPrimitive $time = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
