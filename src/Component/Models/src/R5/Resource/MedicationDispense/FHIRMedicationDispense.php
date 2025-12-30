<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRAllLanguagesType;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRAnnotation;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableReference;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRDosage;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRIdentifier;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRMedicationDispenseStatusCodesType;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRMeta;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRNarrative;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRQuantity;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDateTime;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRMarkdown;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRUri;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Health Level Seven International (Pharmacy)
 *
 * @see http://hl7.org/fhir/StructureDefinition/MedicationDispense
 *
 * @description Indicates that a medication product is to be or has been dispensed for a named person/patient.  This includes a description of the medication product (supply) provided and the instructions for administering the medication.  The medication dispense is the result of a pharmacy system responding to a medication order.
 */
#[FhirResource(
    type: 'MedicationDispense',
    version: '5.0.0',
    url: 'http://hl7.org/fhir/StructureDefinition/MedicationDispense',
    fhirVersion: 'R5',
)]
class FHIRMedicationDispense extends FHIRDomainResource
{
    public function __construct(
        /** @var string|null id Logical id of this artifact */
        public ?string $id = null,
        /** @var FHIRMeta|null meta Metadata about the resource */
        public ?FHIRMeta $meta = null,
        /** @var FHIRUri|null implicitRules A set of rules under which this content was created */
        public ?FHIRUri $implicitRules = null,
        /** @var FHIRAllLanguagesType|null language Language of the resource content */
        public ?FHIRAllLanguagesType $language = null,
        /** @var FHIRNarrative|null text Text summary of the resource, for human interpretation */
        public ?FHIRNarrative $text = null,
        /** @var array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRResource> contained Contained, inline Resources */
        public array $contained = [],
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored */
        public array $modifierExtension = [],
        /** @var array<FHIRIdentifier> identifier External identifier */
        public array $identifier = [],
        /** @var array<FHIRReference> basedOn Plan that is fulfilled by this dispense */
        public array $basedOn = [],
        /** @var array<FHIRReference> partOf Event that dispense is part of */
        public array $partOf = [],
        /** @var FHIRMedicationDispenseStatusCodesType|null status preparation | in-progress | cancelled | on-hold | completed | entered-in-error | stopped | declined | unknown */
        #[NotBlank]
        public ?FHIRMedicationDispenseStatusCodesType $status = null,
        /** @var FHIRCodeableReference|null notPerformedReason Why a dispense was not performed */
        public ?FHIRCodeableReference $notPerformedReason = null,
        /** @var FHIRDateTime|null statusChanged When the status changed */
        public ?FHIRDateTime $statusChanged = null,
        /** @var array<FHIRCodeableConcept> category Type of medication dispense */
        public array $category = [],
        /** @var FHIRCodeableReference|null medication What medication was supplied */
        #[NotBlank]
        public ?FHIRCodeableReference $medication = null,
        /** @var FHIRReference|null subject Who the dispense is for */
        #[NotBlank]
        public ?FHIRReference $subject = null,
        /** @var FHIRReference|null encounter Encounter associated with event */
        public ?FHIRReference $encounter = null,
        /** @var array<FHIRReference> supportingInformation Information that supports the dispensing of the medication */
        public array $supportingInformation = [],
        /** @var array<FHIRMedicationDispensePerformer> performer Who performed event */
        public array $performer = [],
        /** @var FHIRReference|null location Where the dispense occurred */
        public ?FHIRReference $location = null,
        /** @var array<FHIRReference> authorizingPrescription Medication order that authorizes the dispense */
        public array $authorizingPrescription = [],
        /** @var FHIRCodeableConcept|null type Trial fill, partial fill, emergency fill, etc */
        public ?FHIRCodeableConcept $type = null,
        /** @var FHIRQuantity|null quantity Amount dispensed */
        public ?FHIRQuantity $quantity = null,
        /** @var FHIRQuantity|null daysSupply Amount of medication expressed as a timing amount */
        public ?FHIRQuantity $daysSupply = null,
        /** @var FHIRDateTime|null recorded When the recording of the dispense started */
        public ?FHIRDateTime $recorded = null,
        /** @var FHIRDateTime|null whenPrepared When product was packaged and reviewed */
        public ?FHIRDateTime $whenPrepared = null,
        /** @var FHIRDateTime|null whenHandedOver When product was given out */
        public ?FHIRDateTime $whenHandedOver = null,
        /** @var FHIRReference|null destination Where the medication was/will be sent */
        public ?FHIRReference $destination = null,
        /** @var array<FHIRReference> receiver Who collected the medication or where the medication was delivered */
        public array $receiver = [],
        /** @var array<FHIRAnnotation> note Information about the dispense */
        public array $note = [],
        /** @var FHIRMarkdown|null renderedDosageInstruction Full representation of the dosage instructions */
        public ?FHIRMarkdown $renderedDosageInstruction = null,
        /** @var array<FHIRDosage> dosageInstruction How the medication is to be used by the patient or administered by the caregiver */
        public array $dosageInstruction = [],
        /** @var FHIRMedicationDispenseSubstitution|null substitution Whether a substitution was performed on the dispense */
        public ?FHIRMedicationDispenseSubstitution $substitution = null,
        /** @var array<FHIRReference> eventHistory A list of relevant lifecycle events */
        public array $eventHistory = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
