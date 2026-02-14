<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

/**
 * @author Health Level Seven International (Health Care Devices)
 * @see http://hl7.org/fhir/StructureDefinition/DeviceMetric
 * @description Describes a measurement, calculation or setting capability of a medical device.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource(type: 'DeviceMetric', version: '4.0.1', url: 'http://hl7.org/fhir/StructureDefinition/DeviceMetric', fhirVersion: 'R4')]
class DeviceMetricResource extends DomainResourceResource
{
	public function __construct(
		/** @var null|string id Logical id of this artifact */
		public ?string $id = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Meta meta Metadata about the resource */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Meta $meta = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive implicitRules A set of rules under which this content was created */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive $implicitRules = null,
		/** @var null|string language Language of the resource content */
		public ?string $language = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Narrative text Text summary of the resource, for human interpretation */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Narrative $text = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\ResourceResource> contained Contained, inline Resources */
		public array $contained = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> modifierExtension Extensions that cannot be ignored */
		public array $modifierExtension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier> identifier Instance identifier */
		public array $identifier = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept type Identity of metric, for example Heart Rate or PEEP Setting */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $type = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept unit Unit of Measure for the Metric */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $unit = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference source Describes the link to the source Device */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference $source = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference parent Describes the link to the parent Device */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference $parent = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\DeviceMetricOperationalStatusType operationalStatus on | off | standby | entered-in-error */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\DeviceMetricOperationalStatusType $operationalStatus = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\DeviceMetricColorType color black | red | green | yellow | blue | magenta | cyan | white */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\DeviceMetricColorType $color = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\DeviceMetricCategoryType category measurement | setting | calculation | unspecified */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\DeviceMetricCategoryType $category = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Timing measurementPeriod Describes the measurement repetition time */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Timing $measurementPeriod = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\DeviceMetric\DeviceMetricCalibration> calibration Describes the calibrations that have been performed or that are required to be performed */
		public array $calibration = [],
	) {
		parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
	}
}
