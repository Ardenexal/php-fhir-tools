<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

/**
 * @author Health Level Seven International (Health Care Devices)
 * @see http://hl7.org/fhir/StructureDefinition/DeviceMetric
 * @description Describes a measurement, calculation or setting capability of a medical device.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource(type: 'DeviceMetric', version: '4.3.0', url: 'http://hl7.org/fhir/StructureDefinition/DeviceMetric', fhirVersion: 'R4B')]
class FHIRDeviceMetric extends FHIRDomainResource
{
	public function __construct(
		/** @var null|string id Logical id of this artifact */
		public ?string $id = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRMeta meta Metadata about the resource */
		public ?FHIRMeta $meta = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRUri implicitRules A set of rules under which this content was created */
		public ?FHIRUri $implicitRules = null,
		/** @var null|string language Language of the resource content */
		public ?string $language = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRNarrative text Text summary of the resource, for human interpretation */
		public ?FHIRNarrative $text = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRResource> contained Contained, inline Resources */
		public array $contained = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored */
		public array $modifierExtension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRIdentifier> identifier Instance identifier */
		public array $identifier = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCodeableConcept type Identity of metric, for example Heart Rate or PEEP Setting */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?FHIRCodeableConcept $type = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCodeableConcept unit Unit of Measure for the Metric */
		public ?FHIRCodeableConcept $unit = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRReference source Describes the link to the source Device */
		public ?FHIRReference $source = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRReference parent Describes the link to the parent Device */
		public ?FHIRReference $parent = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRDeviceMetricOperationalStatusType operationalStatus on | off | standby | entered-in-error */
		public ?FHIRDeviceMetricOperationalStatusType $operationalStatus = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRDeviceMetricColorType color black | red | green | yellow | blue | magenta | cyan | white */
		public ?FHIRDeviceMetricColorType $color = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRDeviceMetricCategoryType category measurement | setting | calculation | unspecified */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?FHIRDeviceMetricCategoryType $category = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRTiming measurementPeriod Describes the measurement repetition time */
		public ?FHIRTiming $measurementPeriod = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRDeviceMetricCalibration> calibration Describes the calibrations that have been performed or that are required to be performed */
		public array $calibration = [],
	) {
		parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
	}
}
