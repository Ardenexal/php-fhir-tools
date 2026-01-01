<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

/**
 * @author Health Level Seven International (Patient Care)
 * @see http://hl7.org/fhir/StructureDefinition/AllergyIntolerance
 * @description Risk of harmful or undesirable, physiological response which is unique to an individual and associated with exposure to a substance.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource(
	type: 'AllergyIntolerance',
	version: '4.0.1',
	url: 'http://hl7.org/fhir/StructureDefinition/AllergyIntolerance',
	fhirVersion: 'R4',
)]
class FHIRAllergyIntolerance extends FHIRDomainResource
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
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRIdentifier> identifier External ids for this item */
		public array $identifier = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept clinicalStatus active | inactive | resolved */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept $clinicalStatus = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept verificationStatus unconfirmed | confirmed | refuted | entered-in-error */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept $verificationStatus = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRAllergyIntoleranceTypeType type allergy | intolerance - Underlying mechanism (if known) */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRAllergyIntoleranceTypeType $type = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRAllergyIntoleranceCategoryType> category food | medication | environment | biologic */
		public array $category = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRAllergyIntoleranceCriticalityType criticality low | high | unable-to-assess */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRAllergyIntoleranceCriticalityType $criticality = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept code Code that identifies the allergy or intolerance */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept $code = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRReference patient Who the sensitivity is for */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRReference $patient = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRReference encounter Encounter when the allergy or intolerance was asserted */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRReference $encounter = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRDateTime|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRAge|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRPeriod|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRRange|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRString|string onsetX When allergy or intolerance was identified */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRDateTime|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRAge|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRPeriod|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRRange|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRString|string|null $onsetX = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRDateTime recordedDate Date first version of the resource instance was recorded */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRDateTime $recordedDate = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRReference recorder Who recorded the sensitivity */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRReference $recorder = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRReference asserter Source of the information about the allergy */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRReference $asserter = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRDateTime lastOccurrence Date(/time) of last known occurrence of a reaction */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRDateTime $lastOccurrence = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRAnnotation> note Additional text not captured in other fields */
		public array $note = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRAllergyIntoleranceReaction> reaction Adverse Reaction Events linked to exposure to substance */
		public array $reaction = [],
	) {
		parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
	}
}
