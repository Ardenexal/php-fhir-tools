<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Annotation;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Dosage;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\MedicationDispenseStatusCodesType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Meta;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Narrative;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Quantity;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\MedicationDispense\MedicationDispensePerformer;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\MedicationDispense\MedicationDispenseSubstitution;
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
    version: '4.0.1',
    url: 'http://hl7.org/fhir/StructureDefinition/MedicationDispense',
    fhirVersion: 'R4',
)]
class MedicationDispenseResource extends DomainResourceResource
{
    public function __construct(
        /** @var string|null id Logical id of this artifact */
        public ?string $id = null,
        /** @var Meta|null meta Metadata about the resource */
        public ?Meta $meta = null,
        /** @var UriPrimitive|null implicitRules A set of rules under which this content was created */
        public ?UriPrimitive $implicitRules = null,
        /** @var string|null language Language of the resource content */
        public ?string $language = null,
        /** @var Narrative|null text Text summary of the resource, for human interpretation */
        public ?Narrative $text = null,
        /** @var array<ResourceResource> contained Contained, inline Resources */
        public array $contained = [],
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored */
        public array $modifierExtension = [],
        /** @var array<Identifier> identifier External identifier */
        public array $identifier = [],
        /** @var array<Reference> partOf Event that dispense is part of */
        public array $partOf = [],
        /** @var MedicationDispenseStatusCodesType|null status preparation | in-progress | cancelled | on-hold | completed | entered-in-error | stopped | declined | unknown */
        #[NotBlank]
        public ?MedicationDispenseStatusCodesType $status = null,
        /** @var CodeableConcept|Reference|null statusReasonX Why a dispense was not performed */
        public CodeableConcept|Reference|null $statusReasonX = null,
        /** @var CodeableConcept|null category Type of medication dispense */
        public ?CodeableConcept $category = null,
        /** @var CodeableConcept|Reference|null medicationX What medication was supplied */
        #[NotBlank]
        public CodeableConcept|Reference|null $medicationX = null,
        /** @var Reference|null subject Who the dispense is for */
        public ?Reference $subject = null,
        /** @var Reference|null context Encounter / Episode associated with event */
        public ?Reference $context = null,
        /** @var array<Reference> supportingInformation Information that supports the dispensing of the medication */
        public array $supportingInformation = [],
        /** @var array<MedicationDispensePerformer> performer Who performed event */
        public array $performer = [],
        /** @var Reference|null location Where the dispense occurred */
        public ?Reference $location = null,
        /** @var array<Reference> authorizingPrescription Medication order that authorizes the dispense */
        public array $authorizingPrescription = [],
        /** @var CodeableConcept|null type Trial fill, partial fill, emergency fill, etc. */
        public ?CodeableConcept $type = null,
        /** @var Quantity|null quantity Amount dispensed */
        public ?Quantity $quantity = null,
        /** @var Quantity|null daysSupply Amount of medication expressed as a timing amount */
        public ?Quantity $daysSupply = null,
        /** @var DateTimePrimitive|null whenPrepared When product was packaged and reviewed */
        public ?DateTimePrimitive $whenPrepared = null,
        /** @var DateTimePrimitive|null whenHandedOver When product was given out */
        public ?DateTimePrimitive $whenHandedOver = null,
        /** @var Reference|null destination Where the medication was sent */
        public ?Reference $destination = null,
        /** @var array<Reference> receiver Who collected the medication */
        public array $receiver = [],
        /** @var array<Annotation> note Information about the dispense */
        public array $note = [],
        /** @var array<Dosage> dosageInstruction How the medication is to be used by the patient or administered by the caregiver */
        public array $dosageInstruction = [],
        /** @var MedicationDispenseSubstitution|null substitution Whether a substitution was performed on the dispense */
        public ?MedicationDispenseSubstitution $substitution = null,
        /** @var array<Reference> detectedIssue Clinical issue with action */
        public array $detectedIssue = [],
        /** @var array<Reference> eventHistory A list of relevant lifecycle events */
        public array $eventHistory = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
