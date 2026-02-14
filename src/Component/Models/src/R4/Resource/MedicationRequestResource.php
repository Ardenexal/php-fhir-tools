<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

/**
 * @author Health Level Seven International (Pharmacy)
 * @see http://hl7.org/fhir/StructureDefinition/MedicationRequest
 * @description An order or request for both supply of the medication and the instructions for administration of the medication to a patient. The resource is called "MedicationRequest" rather than "MedicationPrescription" or "MedicationOrder" to generalize the use across inpatient and outpatient settings, including care plans, etc., and to harmonize with workflow patterns.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource(
	type: 'MedicationRequest',
	version: '4.0.1',
	url: 'http://hl7.org/fhir/StructureDefinition/MedicationRequest',
	fhirVersion: 'R4',
)]
class MedicationRequestResource extends DomainResourceResource
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
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier> identifier External ids for this request */
		public array $identifier = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\MedicationrequestStatusType status active | on-hold | cancelled | completed | entered-in-error | stopped | draft | unknown */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\MedicationrequestStatusType $status = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept statusReason Reason for current status */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $statusReason = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\MedicationRequestIntentType intent proposal | plan | order | original-order | reflex-order | filler-order | instance-order | option */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\MedicationRequestIntentType $intent = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept> category Type of medication usage */
		public array $category = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\RequestPriorityType priority routine | urgent | asap | stat */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\RequestPriorityType $priority = null,
		/** @var null|bool doNotPerform True if request is prohibiting action */
		public ?bool $doNotPerform = null,
		/** @var null|bool|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference reportedX Reported rather than primary record */
		public bool|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference|null $reportedX = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference medicationX Medication to be taken */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public \Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference|null $medicationX = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference subject Who or group medication request is for */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference $subject = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference encounter Encounter created as part of encounter/admission/stay */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference $encounter = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference> supportingInformation Information to support ordering of the medication */
		public array $supportingInformation = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive authoredOn When request was initially authored */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive $authoredOn = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference requester Who/What requested the Request */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference $requester = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference performer Intended performer of administration */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference $performer = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept performerType Desired kind of performer of the medication administration */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $performerType = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference recorder Person who entered the request */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference $recorder = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept> reasonCode Reason or indication for ordering or not ordering the medication */
		public array $reasonCode = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference> reasonReference Condition or observation that supports why the prescription is being written */
		public array $reasonReference = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Primitive\CanonicalPrimitive> instantiatesCanonical Instantiates FHIR protocol or definition */
		public array $instantiatesCanonical = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive> instantiatesUri Instantiates external protocol or definition */
		public array $instantiatesUri = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference> basedOn What request fulfills */
		public array $basedOn = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier groupIdentifier Composite request this is part of */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier $groupIdentifier = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept courseOfTherapyType Overall pattern of medication administration */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $courseOfTherapyType = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference> insurance Associated insurance coverage */
		public array $insurance = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Annotation> note Information about the prescription */
		public array $note = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Dosage> dosageInstruction How the medication should be taken */
		public array $dosageInstruction = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\MedicationRequest\MedicationRequestDispenseRequest dispenseRequest Medication supply authorization */
		public ?MedicationRequest\MedicationRequestDispenseRequest $dispenseRequest = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\MedicationRequest\MedicationRequestSubstitution substitution Any restrictions on medication substitution */
		public ?MedicationRequest\MedicationRequestSubstitution $substitution = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference priorPrescription An order/prescription that is being replaced */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference $priorPrescription = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference> detectedIssue Clinical Issue with action */
		public array $detectedIssue = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference> eventHistory A list of events of interest in the lifecycle */
		public array $eventHistory = [],
	) {
		parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
	}
}
