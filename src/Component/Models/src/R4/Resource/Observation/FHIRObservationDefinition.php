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
class FHIRObservationDefinition extends FHIRDomainResource
{
	public function __construct(
		/** @var null|string id Logical id of this artifact */
		public ?string $id = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRMeta meta Metadata about the resource */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRMeta $meta = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRUri implicitRules A set of rules under which this content was created */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRUri $implicitRules = null,
		/** @var null|string language Language of the resource content */
		public ?string $language = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRNarrative text Text summary of the resource, for human interpretation */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRNarrative $text = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRResource> contained Contained, inline Resources */
		public array $contained = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExtension> modifierExtension Extensions that cannot be ignored */
		public array $modifierExtension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept> category Category of observation */
		public array $category = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept code Type of observation (code / type) */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept $code = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRIdentifier> identifier Business identifier for this ObservationDefinition instance */
		public array $identifier = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRObservationDataTypeType> permittedDataType Quantity | CodeableConcept | string | boolean | integer | Range | Ratio | SampledData | time | dateTime | Period */
		public array $permittedDataType = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRBoolean multipleResultsAllowed Multiple results allowed */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRBoolean $multipleResultsAllowed = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept method Method used to produce the observation */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept $method = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRString|string preferredReportName Preferred report name */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRString|string|null $preferredReportName = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRObservationDefinitionQuantitativeDetails quantitativeDetails Characteristics of quantitative results */
		public ?FHIRObservationDefinitionQuantitativeDetails $quantitativeDetails = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRObservationDefinitionQualifiedInterval> qualifiedInterval Qualified range for continuous and ordinal observation results */
		public array $qualifiedInterval = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRReference validCodedValueSet Value set of valid coded values for the observations conforming to this ObservationDefinition */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRReference $validCodedValueSet = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRReference normalCodedValueSet Value set of normal coded values for the observations conforming to this ObservationDefinition */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRReference $normalCodedValueSet = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRReference abnormalCodedValueSet Value set of abnormal coded values for the observations conforming to this ObservationDefinition */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRReference $abnormalCodedValueSet = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRReference criticalCodedValueSet Value set of critical coded values for the observations conforming to this ObservationDefinition */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRReference $criticalCodedValueSet = null,
	) {
		parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
	}
}
