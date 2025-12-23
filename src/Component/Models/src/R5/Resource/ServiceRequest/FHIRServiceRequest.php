<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

/**
 * @author Health Level Seven International (Orders and Observations)
 * @see http://hl7.org/fhir/StructureDefinition/ServiceRequest
 * @description A record of a request for service such as diagnostic investigations, treatments, or operations to be performed.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource(
	type: 'ServiceRequest',
	version: '5.0.0',
	url: 'http://hl7.org/fhir/StructureDefinition/ServiceRequest',
	fhirVersion: 'R5',
)]
class FHIRServiceRequest extends FHIRDomainResource
{
	public function __construct(
		/** @var null|string id Logical id of this artifact */
		public ?string $id = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRMeta meta Metadata about the resource */
		public ?FHIRMeta $meta = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRUri implicitRules A set of rules under which this content was created */
		public ?FHIRUri $implicitRules = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRAllLanguagesType language Language of the resource content */
		public ?FHIRAllLanguagesType $language = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRNarrative text Text summary of the resource, for human interpretation */
		public ?FHIRNarrative $text = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRResource> contained Contained, inline Resources */
		public array $contained = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored */
		public array $modifierExtension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRIdentifier> identifier Identifiers assigned to this order */
		public array $identifier = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCanonical> instantiatesCanonical Instantiates FHIR protocol or definition */
		public array $instantiatesCanonical = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRUri> instantiatesUri Instantiates external protocol or definition */
		public array $instantiatesUri = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference> basedOn What request fulfills */
		public array $basedOn = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference> replaces What request replaces */
		public array $replaces = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRIdentifier requisition Composite Request ID */
		public ?FHIRIdentifier $requisition = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRRequestStatusType status draft | active | on-hold | revoked | completed | entered-in-error | unknown */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?FHIRRequestStatusType $status = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRRequestIntentType intent proposal | plan | directive | order + */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?FHIRRequestIntentType $intent = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept> category Classification of service */
		public array $category = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRRequestPriorityType priority routine | urgent | asap | stat */
		public ?FHIRRequestPriorityType $priority = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBoolean doNotPerform True if service/procedure should not be performed */
		public ?FHIRBoolean $doNotPerform = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableReference code What is being requested/ordered */
		public ?FHIRCodeableReference $code = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRServiceRequestOrderDetail> orderDetail Additional order information */
		public array $orderDetail = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRQuantity|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRRatio|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRRange quantityX Service amount */
		public FHIRQuantity|FHIRRatio|FHIRRange|null $quantityX = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference subject Individual or Entity the service is ordered for */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?FHIRReference $subject = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference> focus What the service request is about, when it is not about the subject of record */
		public array $focus = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference encounter Encounter in which the request was created */
		public ?FHIRReference $encounter = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRDateTime|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRPeriod|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRTiming occurrenceX When service should occur */
		public FHIRDateTime|FHIRPeriod|FHIRTiming|null $occurrenceX = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBoolean|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept asNeededX Preconditions for service */
		public FHIRBoolean|FHIRCodeableConcept|null $asNeededX = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRDateTime authoredOn Date request signed */
		public ?FHIRDateTime $authoredOn = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference requester Who/what is requesting service */
		public ?FHIRReference $requester = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept performerType Performer role */
		public ?FHIRCodeableConcept $performerType = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference> performer Requested performer */
		public array $performer = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableReference> location Requested location */
		public array $location = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableReference> reason Explanation/Justification for procedure or service */
		public array $reason = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference> insurance Associated insurance coverage */
		public array $insurance = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableReference> supportingInfo Additional clinical information */
		public array $supportingInfo = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference> specimen Procedure Samples */
		public array $specimen = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept> bodySite Coded location on Body */
		public array $bodySite = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference bodyStructure BodyStructure-based location on the body */
		public ?FHIRReference $bodyStructure = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRAnnotation> note Comments */
		public array $note = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRServiceRequestPatientInstruction> patientInstruction Patient or consumer-oriented instructions */
		public array $patientInstruction = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference> relevantHistory Request provenance */
		public array $relevantHistory = [],
	) {
		parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
	}
}
