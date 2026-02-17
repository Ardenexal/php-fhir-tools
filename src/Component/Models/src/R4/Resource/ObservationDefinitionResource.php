<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

/**
 * @author Health Level Seven International (Orders and Observations)
 * @see http://hl7.org/fhir/StructureDefinition/ObservationDefinition
 * @description Set of definitional characteristics for a kind of observation or measurement produced or consumed by an orderable health care service.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource(
	type: 'ObservationDefinition',
	version: '4.0.1',
	url: 'http://hl7.org/fhir/StructureDefinition/ObservationDefinition',
	fhirVersion: 'R4',
)]
class ObservationDefinitionResource extends DomainResourceResource
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
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept> category Category of observation */
		public array $category = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept code Type of observation (code / type) */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $code = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier> identifier Business identifier for this ObservationDefinition instance */
		public array $identifier = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\ObservationDataTypeType> permittedDataType Quantity | CodeableConcept | string | boolean | integer | Range | Ratio | SampledData | time | dateTime | Period */
		public array $permittedDataType = [],
		/** @var null|bool multipleResultsAllowed Multiple results allowed */
		public ?bool $multipleResultsAllowed = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept method Method used to produce the observation */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $method = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string preferredReportName Preferred report name */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string|null $preferredReportName = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\ObservationDefinition\ObservationDefinitionQuantitativeDetails quantitativeDetails Characteristics of quantitative results */
		public ?ObservationDefinition\ObservationDefinitionQuantitativeDetails $quantitativeDetails = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\ObservationDefinition\ObservationDefinitionQualifiedInterval> qualifiedInterval Qualified range for continuous and ordinal observation results */
		public array $qualifiedInterval = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference validCodedValueSet Value set of valid coded values for the observations conforming to this ObservationDefinition */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference $validCodedValueSet = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference normalCodedValueSet Value set of normal coded values for the observations conforming to this ObservationDefinition */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference $normalCodedValueSet = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference abnormalCodedValueSet Value set of abnormal coded values for the observations conforming to this ObservationDefinition */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference $abnormalCodedValueSet = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference criticalCodedValueSet Value set of critical coded values for the observations conforming to this ObservationDefinition */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference $criticalCodedValueSet = null,
	) {
		parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
	}
}
