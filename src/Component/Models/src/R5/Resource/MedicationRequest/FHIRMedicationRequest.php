<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRAnnotation;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableReference;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRDosage;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRIdentifier;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRMeta;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRNarrative;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRPeriod;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRBoolean;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDateTime;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRMarkdown;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRUri;
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
    version: '5.0.0',
    url: 'http://hl7.org/fhir/StructureDefinition/MedicationRequest',
    fhirVersion: 'R5',
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
        /** @var array<FHIRIdentifier> identifier External ids for this request */
        public array $identifier = [],
        /** @var array<FHIRReference> basedOn A plan or request that is fulfilled in whole or in part by this medication request */
        public array $basedOn = [],
        /** @var FHIRReference|null priorPrescription Reference to an order/prescription that is being replaced by this MedicationRequest */
        public ?FHIRReference $priorPrescription = null,
        /** @var FHIRIdentifier|null groupIdentifier Composite request this is part of */
        public ?FHIRIdentifier $groupIdentifier = null,
        /** @var FHIRMedicationrequestStatusType|null status active | on-hold | ended | stopped | completed | cancelled | entered-in-error | draft | unknown */
        #[NotBlank]
        public ?FHIRMedicationrequestStatusType $status = null,
        /** @var FHIRCodeableConcept|null statusReason Reason for current status */
        public ?FHIRCodeableConcept $statusReason = null,
        /** @var FHIRDateTime|null statusChanged When the status was changed */
        public ?FHIRDateTime $statusChanged = null,
        /** @var FHIRMedicationRequestIntentType|null intent proposal | plan | order | original-order | reflex-order | filler-order | instance-order | option */
        #[NotBlank]
        public ?FHIRMedicationRequestIntentType $intent = null,
        /** @var array<FHIRCodeableConcept> category Grouping or category of medication request */
        public array $category = [],
        /** @var FHIRRequestPriorityType|null priority routine | urgent | asap | stat */
        public ?FHIRRequestPriorityType $priority = null,
        /** @var FHIRBoolean|null doNotPerform True if patient is to stop taking or not to start taking the medication */
        public ?FHIRBoolean $doNotPerform = null,
        /** @var FHIRCodeableReference|null medication Medication to be taken */
        #[NotBlank]
        public ?FHIRCodeableReference $medication = null,
        /** @var FHIRReference|null subject Individual or group for whom the medication has been requested */
        #[NotBlank]
        public ?FHIRReference $subject = null,
        /** @var array<FHIRReference> informationSource The person or organization who provided the information about this request, if the source is someone other than the requestor */
        public array $informationSource = [],
        /** @var FHIRReference|null encounter Encounter created as part of encounter/admission/stay */
        public ?FHIRReference $encounter = null,
        /** @var array<FHIRReference> supportingInformation Information to support fulfilling of the medication */
        public array $supportingInformation = [],
        /** @var FHIRDateTime|null authoredOn When request was initially authored */
        public ?FHIRDateTime $authoredOn = null,
        /** @var FHIRReference|null requester Who/What requested the Request */
        public ?FHIRReference $requester = null,
        /** @var FHIRBoolean|null reported Reported rather than primary record */
        public ?FHIRBoolean $reported = null,
        /** @var FHIRCodeableConcept|null performerType Desired kind of performer of the medication administration */
        public ?FHIRCodeableConcept $performerType = null,
        /** @var array<FHIRReference> performer Intended performer of administration */
        public array $performer = [],
        /** @var array<FHIRCodeableReference> device Intended type of device for the administration */
        public array $device = [],
        /** @var FHIRReference|null recorder Person who entered the request */
        public ?FHIRReference $recorder = null,
        /** @var array<FHIRCodeableReference> reason Reason or indication for ordering or not ordering the medication */
        public array $reason = [],
        /** @var FHIRCodeableConcept|null courseOfTherapyType Overall pattern of medication administration */
        public ?FHIRCodeableConcept $courseOfTherapyType = null,
        /** @var array<FHIRReference> insurance Associated insurance coverage */
        public array $insurance = [],
        /** @var array<FHIRAnnotation> note Information about the prescription */
        public array $note = [],
        /** @var FHIRMarkdown|null renderedDosageInstruction Full representation of the dosage instructions */
        public ?FHIRMarkdown $renderedDosageInstruction = null,
        /** @var FHIRPeriod|null effectiveDosePeriod Period over which the medication is to be taken */
        public ?FHIRPeriod $effectiveDosePeriod = null,
        /** @var array<FHIRDosage> dosageInstruction Specific instructions for how the medication should be taken */
        public array $dosageInstruction = [],
        /** @var FHIRMedicationRequestDispenseRequest|null dispenseRequest Medication supply authorization */
        public ?FHIRMedicationRequestDispenseRequest $dispenseRequest = null,
        /** @var FHIRMedicationRequestSubstitution|null substitution Any restrictions on medication substitution */
        public ?FHIRMedicationRequestSubstitution $substitution = null,
        /** @var array<FHIRReference> eventHistory A list of events of interest in the lifecycle */
        public array $eventHistory = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
