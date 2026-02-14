<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

/**
 * @author Health Level Seven International (Pharmacy)
 * @see http://hl7.org/fhir/StructureDefinition/MedicationDispense
 * @description Indicates that a medication product is to be or has been dispensed for a named person/patient.  This includes a description of the medication product (supply) provided and the instructions for administering the medication.  The medication dispense is the result of a pharmacy system responding to a medication order.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource(
	type: 'MedicationDispense',
	version: '4.0.1',
	url: 'http://hl7.org/fhir/StructureDefinition/MedicationDispense',
	fhirVersion: 'R4',
)]
class MedicationDispenseResource extends DomainResourceResource
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
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier> identifier External identifier */
		public array $identifier = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference> partOf Event that dispense is part of */
		public array $partOf = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\MedicationDispenseStatusCodesType status preparation | in-progress | cancelled | on-hold | completed | entered-in-error | stopped | declined | unknown */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\MedicationDispenseStatusCodesType $status = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference statusReasonX Why a dispense was not performed */
		public \Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference|null $statusReasonX = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept category Type of medication dispense */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $category = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference medicationX What medication was supplied */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public \Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference|null $medicationX = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference subject Who the dispense is for */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference $subject = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference context Encounter / Episode associated with event */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference $context = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference> supportingInformation Information that supports the dispensing of the medication */
		public array $supportingInformation = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\MedicationDispense\MedicationDispensePerformer> performer Who performed event */
		public array $performer = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference location Where the dispense occurred */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference $location = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference> authorizingPrescription Medication order that authorizes the dispense */
		public array $authorizingPrescription = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept type Trial fill, partial fill, emergency fill, etc. */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $type = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Quantity quantity Amount dispensed */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Quantity $quantity = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Quantity daysSupply Amount of medication expressed as a timing amount */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Quantity $daysSupply = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive whenPrepared When product was packaged and reviewed */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive $whenPrepared = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive whenHandedOver When product was given out */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive $whenHandedOver = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference destination Where the medication was sent */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference $destination = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference> receiver Who collected the medication */
		public array $receiver = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Annotation> note Information about the dispense */
		public array $note = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Dosage> dosageInstruction How the medication is to be used by the patient or administered by the caregiver */
		public array $dosageInstruction = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\MedicationDispense\MedicationDispenseSubstitution substitution Whether a substitution was performed on the dispense */
		public ?MedicationDispense\MedicationDispenseSubstitution $substitution = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference> detectedIssue Clinical issue with action */
		public array $detectedIssue = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference> eventHistory A list of relevant lifecycle events */
		public array $eventHistory = [],
	) {
		parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
	}
}
