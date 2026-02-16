<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Annotation;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Dosage;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\MedicationRequestIntentType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\MedicationrequestStatusType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Meta;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Narrative;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\RequestPriorityType;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\CanonicalPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\MedicationRequest\MedicationRequestDispenseRequest;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\MedicationRequest\MedicationRequestSubstitution;
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
class MedicationRequestResource extends DomainResourceResource
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
        /** @var array<Identifier> identifier External ids for this request */
        public array $identifier = [],
        /** @var MedicationrequestStatusType|null status active | on-hold | cancelled | completed | entered-in-error | stopped | draft | unknown */
        #[NotBlank]
        public ?MedicationrequestStatusType $status = null,
        /** @var CodeableConcept|null statusReason Reason for current status */
        public ?CodeableConcept $statusReason = null,
        /** @var MedicationRequestIntentType|null intent proposal | plan | order | original-order | reflex-order | filler-order | instance-order | option */
        #[NotBlank]
        public ?MedicationRequestIntentType $intent = null,
        /** @var array<CodeableConcept> category Type of medication usage */
        public array $category = [],
        /** @var RequestPriorityType|null priority routine | urgent | asap | stat */
        public ?RequestPriorityType $priority = null,
        /** @var bool|null doNotPerform True if request is prohibiting action */
        public ?bool $doNotPerform = null,
        /** @var bool|Reference|null reportedX Reported rather than primary record */
        public bool|Reference|null $reportedX = null,
        /** @var CodeableConcept|Reference|null medicationX Medication to be taken */
        #[NotBlank]
        public CodeableConcept|Reference|null $medicationX = null,
        /** @var Reference|null subject Who or group medication request is for */
        #[NotBlank]
        public ?Reference $subject = null,
        /** @var Reference|null encounter Encounter created as part of encounter/admission/stay */
        public ?Reference $encounter = null,
        /** @var array<Reference> supportingInformation Information to support ordering of the medication */
        public array $supportingInformation = [],
        /** @var DateTimePrimitive|null authoredOn When request was initially authored */
        public ?DateTimePrimitive $authoredOn = null,
        /** @var Reference|null requester Who/What requested the Request */
        public ?Reference $requester = null,
        /** @var Reference|null performer Intended performer of administration */
        public ?Reference $performer = null,
        /** @var CodeableConcept|null performerType Desired kind of performer of the medication administration */
        public ?CodeableConcept $performerType = null,
        /** @var Reference|null recorder Person who entered the request */
        public ?Reference $recorder = null,
        /** @var array<CodeableConcept> reasonCode Reason or indication for ordering or not ordering the medication */
        public array $reasonCode = [],
        /** @var array<Reference> reasonReference Condition or observation that supports why the prescription is being written */
        public array $reasonReference = [],
        /** @var array<CanonicalPrimitive> instantiatesCanonical Instantiates FHIR protocol or definition */
        public array $instantiatesCanonical = [],
        /** @var array<UriPrimitive> instantiatesUri Instantiates external protocol or definition */
        public array $instantiatesUri = [],
        /** @var array<Reference> basedOn What request fulfills */
        public array $basedOn = [],
        /** @var Identifier|null groupIdentifier Composite request this is part of */
        public ?Identifier $groupIdentifier = null,
        /** @var CodeableConcept|null courseOfTherapyType Overall pattern of medication administration */
        public ?CodeableConcept $courseOfTherapyType = null,
        /** @var array<Reference> insurance Associated insurance coverage */
        public array $insurance = [],
        /** @var array<Annotation> note Information about the prescription */
        public array $note = [],
        /** @var array<Dosage> dosageInstruction How the medication should be taken */
        public array $dosageInstruction = [],
        /** @var MedicationRequestDispenseRequest|null dispenseRequest Medication supply authorization */
        public ?MedicationRequestDispenseRequest $dispenseRequest = null,
        /** @var MedicationRequestSubstitution|null substitution Any restrictions on medication substitution */
        public ?MedicationRequestSubstitution $substitution = null,
        /** @var Reference|null priorPrescription An order/prescription that is being replaced */
        public ?Reference $priorPrescription = null,
        /** @var array<Reference> detectedIssue Clinical Issue with action */
        public array $detectedIssue = [],
        /** @var array<Reference> eventHistory A list of events of interest in the lifecycle */
        public array $eventHistory = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
