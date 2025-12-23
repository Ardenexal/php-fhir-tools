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
class FHIRMedicationDispense extends FHIRDomainResource
{
	public function __construct(
		/** @var null|string id Logical id of this artifact */
		public ?string $id = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRMeta meta Metadata about the resource */
		public ?FHIRMeta $meta = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRUri implicitRules A set of rules under which this content was created */
		public ?FHIRUri $implicitRules = null,
		/** @var null|string language Language of the resource content */
		public ?string $language = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRNarrative text Text summary of the resource, for human interpretation */
		public ?FHIRNarrative $text = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRResource> contained Contained, inline Resources */
		public array $contained = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored */
		public array $modifierExtension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRIdentifier> identifier External identifier */
		public array $identifier = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRReference> partOf Event that dispense is part of */
		public array $partOf = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRMedicationDispenseStatusCodesType status preparation | in-progress | cancelled | on-hold | completed | entered-in-error | stopped | declined | unknown */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?FHIRMedicationDispenseStatusCodesType $status = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCodeableConcept|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRReference statusReasonX Why a dispense was not performed */
		public FHIRCodeableConcept|FHIRReference|null $statusReasonX = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCodeableConcept category Type of medication dispense */
		public ?FHIRCodeableConcept $category = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCodeableConcept|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRReference medicationX What medication was supplied */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public FHIRCodeableConcept|FHIRReference|null $medicationX = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRReference subject Who the dispense is for */
		public ?FHIRReference $subject = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRReference context Encounter / Episode associated with event */
		public ?FHIRReference $context = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRReference> supportingInformation Information that supports the dispensing of the medication */
		public array $supportingInformation = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRMedicationDispensePerformer> performer Who performed event */
		public array $performer = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRReference location Where the dispense occurred */
		public ?FHIRReference $location = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRReference> authorizingPrescription Medication order that authorizes the dispense */
		public array $authorizingPrescription = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCodeableConcept type Trial fill, partial fill, emergency fill, etc. */
		public ?FHIRCodeableConcept $type = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRQuantity quantity Amount dispensed */
		public ?FHIRQuantity $quantity = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRQuantity daysSupply Amount of medication expressed as a timing amount */
		public ?FHIRQuantity $daysSupply = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRDateTime whenPrepared When product was packaged and reviewed */
		public ?FHIRDateTime $whenPrepared = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRDateTime whenHandedOver When product was given out */
		public ?FHIRDateTime $whenHandedOver = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRReference destination Where the medication was sent */
		public ?FHIRReference $destination = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRReference> receiver Who collected the medication */
		public array $receiver = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRAnnotation> note Information about the dispense */
		public array $note = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRDosage> dosageInstruction How the medication is to be used by the patient or administered by the caregiver */
		public array $dosageInstruction = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRMedicationDispenseSubstitution substitution Whether a substitution was performed on the dispense */
		public ?FHIRMedicationDispenseSubstitution $substitution = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRReference> detectedIssue Clinical issue with action */
		public array $detectedIssue = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRReference> eventHistory A list of relevant lifecycle events */
		public array $eventHistory = [],
	) {
		parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
	}
}
