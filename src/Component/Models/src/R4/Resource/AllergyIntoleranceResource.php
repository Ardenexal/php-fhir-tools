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
class AllergyIntoleranceResource extends DomainResourceResource
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
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier> identifier External ids for this item */
		public array $identifier = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept clinicalStatus active | inactive | resolved */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $clinicalStatus = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept verificationStatus unconfirmed | confirmed | refuted | entered-in-error */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $verificationStatus = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\AllergyIntoleranceTypeType type allergy | intolerance - Underlying mechanism (if known) */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\AllergyIntoleranceTypeType $type = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\AllergyIntoleranceCategoryType> category food | medication | environment | biologic */
		public array $category = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\AllergyIntoleranceCriticalityType criticality low | high | unable-to-assess */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\AllergyIntoleranceCriticalityType $criticality = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept code Code that identifies the allergy or intolerance */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $code = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference patient Who the sensitivity is for */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference $patient = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference encounter Encounter when the allergy or intolerance was asserted */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference $encounter = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Age|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Period|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Range|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string onsetX When allergy or intolerance was identified */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Age|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Period|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Range|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string|null $onsetX = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive recordedDate Date first version of the resource instance was recorded */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive $recordedDate = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference recorder Who recorded the sensitivity */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference $recorder = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference asserter Source of the information about the allergy */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference $asserter = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive lastOccurrence Date(/time) of last known occurrence of a reaction */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive $lastOccurrence = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Annotation> note Additional text not captured in other fields */
		public array $note = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\AllergyIntolerance\AllergyIntoleranceReaction> reaction Adverse Reaction Events linked to exposure to substance */
		public array $reaction = [],
	) {
		parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
	}
}
