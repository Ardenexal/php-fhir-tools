<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

/**
 * @author Health Level Seven International (Public Health and Emergency Response)
 * @see http://hl7.org/fhir/StructureDefinition/Immunization
 * @description Describes the event of a patient being administered a vaccine or a record of an immunization as reported by a patient, a clinician or another party.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource(type: 'Immunization', version: '4.0.1', url: 'http://hl7.org/fhir/StructureDefinition/Immunization', fhirVersion: 'R4')]
class ImmunizationResource extends DomainResourceResource
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
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier> identifier Business identifier */
		public array $identifier = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\ImmunizationStatusCodesType status completed | entered-in-error | not-done */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\ImmunizationStatusCodesType $status = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept statusReason Reason not done */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $statusReason = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept vaccineCode Vaccine product administered */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $vaccineCode = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference patient Who was immunized */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference $patient = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference encounter Encounter immunization was part of */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference $encounter = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string occurrenceX Vaccine administration date */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string|null $occurrenceX = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive recorded When the immunization was first captured in the subject's record */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive $recorded = null,
		/** @var null|bool primarySource Indicates context the data was recorded in */
		public ?bool $primarySource = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept reportOrigin Indicates the source of a secondarily reported record */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $reportOrigin = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference location Where immunization occurred */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference $location = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference manufacturer Vaccine manufacturer */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference $manufacturer = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string lotNumber Vaccine lot number */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string|null $lotNumber = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\DatePrimitive expirationDate Vaccine expiration date */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\DatePrimitive $expirationDate = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept site Body site vaccine  was administered */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $site = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept route How vaccine entered body */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $route = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Quantity doseQuantity Amount of vaccine administered */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Quantity $doseQuantity = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\Immunization\ImmunizationPerformer> performer Who performed event */
		public array $performer = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Annotation> note Additional immunization notes */
		public array $note = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept> reasonCode Why immunization occurred */
		public array $reasonCode = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference> reasonReference Why immunization occurred */
		public array $reasonReference = [],
		/** @var null|bool isSubpotent Dose potency */
		public ?bool $isSubpotent = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept> subpotentReason Reason for being subpotent */
		public array $subpotentReason = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\Immunization\ImmunizationEducation> education Educational material presented to patient */
		public array $education = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept> programEligibility Patient eligibility for a vaccination program */
		public array $programEligibility = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept fundingSource Funding source for the vaccine */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $fundingSource = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\Immunization\ImmunizationReaction> reaction Details of a reaction that follows immunization */
		public array $reaction = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\Immunization\ImmunizationProtocolApplied> protocolApplied Protocol followed by the provider */
		public array $protocolApplied = [],
	) {
		parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
	}
}
