<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRAnnotation;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRDosage;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRIdentifier;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRMeta;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRNarrative;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRReference;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRBoolean;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCanonical;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRDateTime;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRUri;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Health Level Seven International (Pharmacy)
 *
 * @see http://hl7.org/fhir/StructureDefinition/MedicationRequest
 *
 * @description An order or request for both supply of the medication and the instructions for administration of the medication to a patient. The resource is called "MedicationRequest" rather than "MedicationPrescription" or "MedicationOrder" to generalize the use across inpatient and outpatient settings, including care plans, etc., and to harmonize with workflow patterns.
 */
#[FhirResource(
    type: 'MedicationRequest',
    version: '4.0.1',
    url: 'http://hl7.org/fhir/StructureDefinition/MedicationRequest',
    fhirVersion: 'R4',
)]
class FHIRMedicationRequest extends FHIRDomainResource
{
    public function __construct(
        /** @var string|null id Logical id of this artifact */
        public ?string $id = null,
        /** @var FHIRMeta|null meta Metadata about the resource */
        public ?FHIRMeta $meta = null,
        /** @var FHIRUri|null implicitRules A set of rules under which this content was created */
        public ?FHIRUri $implicitRules = null,
        /** @var string|null language Language of the resource content */
        public ?string $language = null,
        /** @var FHIRNarrative|null text Text summary of the resource, for human interpretation */
        public ?FHIRNarrative $text = null,
        /** @var array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRResource> contained Contained, inline Resources */
        public array $contained = [],
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored */
        public array $modifierExtension = [],
        /** @var array<FHIRIdentifier> identifier External ids for this request */
        public array $identifier = [],
        /** @var FHIRMedicationrequestStatusType|null status active | on-hold | cancelled | completed | entered-in-error | stopped | draft | unknown */
        #[NotBlank]
        public ?FHIRMedicationrequestStatusType $status = null,
        /** @var FHIRCodeableConcept|null statusReason Reason for current status */
        public ?FHIRCodeableConcept $statusReason = null,
        /** @var FHIRMedicationRequestIntentType|null intent proposal | plan | order | original-order | reflex-order | filler-order | instance-order | option */
        #[NotBlank]
        public ?FHIRMedicationRequestIntentType $intent = null,
        /** @var array<FHIRCodeableConcept> category Type of medication usage */
        public array $category = [],
        /** @var FHIRRequestPriorityType|null priority routine | urgent | asap | stat */
        public ?FHIRRequestPriorityType $priority = null,
        /** @var FHIRBoolean|null doNotPerform True if request is prohibiting action */
        public ?FHIRBoolean $doNotPerform = null,
        /** @var FHIRBoolean|FHIRReference|null reportedX Reported rather than primary record */
        public FHIRBoolean|FHIRReference|null $reportedX = null,
        /** @var FHIRCodeableConcept|FHIRReference|null medicationX Medication to be taken */
        #[NotBlank]
        public FHIRCodeableConcept|FHIRReference|null $medicationX = null,
        /** @var FHIRReference|null subject Who or group medication request is for */
        #[NotBlank]
        public ?FHIRReference $subject = null,
        /** @var FHIRReference|null encounter Encounter created as part of encounter/admission/stay */
        public ?FHIRReference $encounter = null,
        /** @var array<FHIRReference> supportingInformation Information to support ordering of the medication */
        public array $supportingInformation = [],
        /** @var FHIRDateTime|null authoredOn When request was initially authored */
        public ?FHIRDateTime $authoredOn = null,
        /** @var FHIRReference|null requester Who/What requested the Request */
        public ?FHIRReference $requester = null,
        /** @var FHIRReference|null performer Intended performer of administration */
        public ?FHIRReference $performer = null,
        /** @var FHIRCodeableConcept|null performerType Desired kind of performer of the medication administration */
        public ?FHIRCodeableConcept $performerType = null,
        /** @var FHIRReference|null recorder Person who entered the request */
        public ?FHIRReference $recorder = null,
        /** @var array<FHIRCodeableConcept> reasonCode Reason or indication for ordering or not ordering the medication */
        public array $reasonCode = [],
        /** @var array<FHIRReference> reasonReference Condition or observation that supports why the prescription is being written */
        public array $reasonReference = [],
        /** @var array<FHIRCanonical> instantiatesCanonical Instantiates FHIR protocol or definition */
        public array $instantiatesCanonical = [],
        /** @var array<FHIRUri> instantiatesUri Instantiates external protocol or definition */
        public array $instantiatesUri = [],
        /** @var array<FHIRReference> basedOn What request fulfills */
        public array $basedOn = [],
        /** @var FHIRIdentifier|null groupIdentifier Composite request this is part of */
        public ?FHIRIdentifier $groupIdentifier = null,
        /** @var FHIRCodeableConcept|null courseOfTherapyType Overall pattern of medication administration */
        public ?FHIRCodeableConcept $courseOfTherapyType = null,
        /** @var array<FHIRReference> insurance Associated insurance coverage */
        public array $insurance = [],
        /** @var array<FHIRAnnotation> note Information about the prescription */
        public array $note = [],
        /** @var array<FHIRDosage> dosageInstruction How the medication should be taken */
        public array $dosageInstruction = [],
        /** @var FHIRMedicationRequestDispenseRequest|null dispenseRequest Medication supply authorization */
        public ?FHIRMedicationRequestDispenseRequest $dispenseRequest = null,
        /** @var FHIRMedicationRequestSubstitution|null substitution Any restrictions on medication substitution */
        public ?FHIRMedicationRequestSubstitution $substitution = null,
        /** @var FHIRReference|null priorPrescription An order/prescription that is being replaced */
        public ?FHIRReference $priorPrescription = null,
        /** @var array<FHIRReference> detectedIssue Clinical Issue with action */
        public array $detectedIssue = [],
        /** @var array<FHIRReference> eventHistory A list of events of interest in the lifecycle */
        public array $eventHistory = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
