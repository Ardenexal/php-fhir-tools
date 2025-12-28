<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Health Level Seven International (Pharmacy)
 *
 * @see http://hl7.org/fhir/StructureDefinition/MedicationDispense
 *
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
        /** @var string|null id Logical id of this artifact */
        public ?string $id = null,
        /** @var FHIRMeta|null meta Metadata about the resource */
        public ?\FHIRMeta $meta = null,
        /** @var FHIRUri|null implicitRules A set of rules under which this content was created */
        public ?\FHIRUri $implicitRules = null,
        /** @var string|null language Language of the resource content */
        public ?string $language = null,
        /** @var FHIRNarrative|null text Text summary of the resource, for human interpretation */
        public ?\FHIRNarrative $text = null,
        /** @var array<FHIRResource> contained Contained, inline Resources */
        public array $contained = [],
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored */
        public array $modifierExtension = [],
        /** @var array<FHIRIdentifier> identifier External identifier */
        public array $identifier = [],
        /** @var array<FHIRReference> partOf Event that dispense is part of */
        public array $partOf = [],
        /** @var FHIRMedicationDispenseStatusCodesType|null status preparation | in-progress | cancelled | on-hold | completed | entered-in-error | stopped | declined | unknown */
        #[NotBlank]
        public ?\FHIRMedicationDispenseStatusCodesType $status = null,
        /** @var FHIRCodeableConcept|FHIRReference|null statusReasonX Why a dispense was not performed */
        public \FHIRCodeableConcept|\FHIRReference|null $statusReasonX = null,
        /** @var FHIRCodeableConcept|null category Type of medication dispense */
        public ?\FHIRCodeableConcept $category = null,
        /** @var FHIRCodeableConcept|FHIRReference|null medicationX What medication was supplied */
        #[NotBlank]
        public \FHIRCodeableConcept|\FHIRReference|null $medicationX = null,
        /** @var FHIRReference|null subject Who the dispense is for */
        public ?\FHIRReference $subject = null,
        /** @var FHIRReference|null context Encounter / Episode associated with event */
        public ?\FHIRReference $context = null,
        /** @var array<FHIRReference> supportingInformation Information that supports the dispensing of the medication */
        public array $supportingInformation = [],
        /** @var array<FHIRMedicationDispensePerformer> performer Who performed event */
        public array $performer = [],
        /** @var FHIRReference|null location Where the dispense occurred */
        public ?\FHIRReference $location = null,
        /** @var array<FHIRReference> authorizingPrescription Medication order that authorizes the dispense */
        public array $authorizingPrescription = [],
        /** @var FHIRCodeableConcept|null type Trial fill, partial fill, emergency fill, etc. */
        public ?\FHIRCodeableConcept $type = null,
        /** @var FHIRQuantity|null quantity Amount dispensed */
        public ?\FHIRQuantity $quantity = null,
        /** @var FHIRQuantity|null daysSupply Amount of medication expressed as a timing amount */
        public ?\FHIRQuantity $daysSupply = null,
        /** @var FHIRDateTime|null whenPrepared When product was packaged and reviewed */
        public ?\FHIRDateTime $whenPrepared = null,
        /** @var FHIRDateTime|null whenHandedOver When product was given out */
        public ?\FHIRDateTime $whenHandedOver = null,
        /** @var FHIRReference|null destination Where the medication was sent */
        public ?\FHIRReference $destination = null,
        /** @var array<FHIRReference> receiver Who collected the medication */
        public array $receiver = [],
        /** @var array<FHIRAnnotation> note Information about the dispense */
        public array $note = [],
        /** @var array<FHIRDosage> dosageInstruction How the medication is to be used by the patient or administered by the caregiver */
        public array $dosageInstruction = [],
        /** @var FHIRMedicationDispenseSubstitution|null substitution Whether a substitution was performed on the dispense */
        public ?\FHIRMedicationDispenseSubstitution $substitution = null,
        /** @var array<FHIRReference> detectedIssue Clinical issue with action */
        public array $detectedIssue = [],
        /** @var array<FHIRReference> eventHistory A list of relevant lifecycle events */
        public array $eventHistory = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
