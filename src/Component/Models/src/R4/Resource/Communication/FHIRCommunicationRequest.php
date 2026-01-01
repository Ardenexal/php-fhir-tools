<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

/**
 * @author Health Level Seven International (Patient Care)
 * @see http://hl7.org/fhir/StructureDefinition/CommunicationRequest
 * @description A request to convey information; e.g. the CDS system proposes that an alert be sent to a responsible provider, the CDS system proposes that the public health agency be notified about a reportable condition.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource(
	type: 'CommunicationRequest',
	version: '4.0.1',
	url: 'http://hl7.org/fhir/StructureDefinition/CommunicationRequest',
	fhirVersion: 'R4',
)]
class FHIRCommunicationRequest extends FHIRDomainResource
{
	public function __construct(
		/** @var null|string id Logical id of this artifact */
		public ?string $id = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRMeta meta Metadata about the resource */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRMeta $meta = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRUri implicitRules A set of rules under which this content was created */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRUri $implicitRules = null,
		/** @var null|string language Language of the resource content */
		public ?string $language = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRNarrative text Text summary of the resource, for human interpretation */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRNarrative $text = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRResource> contained Contained, inline Resources */
		public array $contained = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExtension> modifierExtension Extensions that cannot be ignored */
		public array $modifierExtension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRIdentifier> identifier Unique identifier */
		public array $identifier = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRReference> basedOn Fulfills plan or proposal */
		public array $basedOn = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRReference> replaces Request(s) replaced by this request */
		public array $replaces = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRIdentifier groupIdentifier Composite request this is part of */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRIdentifier $groupIdentifier = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRRequestStatusType status draft | active | on-hold | revoked | completed | entered-in-error | unknown */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRRequestStatusType $status = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept statusReason Reason for current status */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept $statusReason = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept> category Message category */
		public array $category = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRRequestPriorityType priority routine | urgent | asap | stat */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRRequestPriorityType $priority = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRBoolean doNotPerform True if request is prohibiting action */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRBoolean $doNotPerform = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept> medium A channel of communication */
		public array $medium = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRReference subject Focus of message */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRReference $subject = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRReference> about Resources that pertain to this communication request */
		public array $about = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRReference encounter Encounter created as part of */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRReference $encounter = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCommunicationRequestPayload> payload Message payload */
		public array $payload = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRDateTime|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRPeriod occurrenceX When scheduled */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRDateTime|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRPeriod|null $occurrenceX = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRDateTime authoredOn When request transitioned to being actionable */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRDateTime $authoredOn = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRReference requester Who/what is requesting service */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRReference $requester = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRReference> recipient Message recipient */
		public array $recipient = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRReference sender Message sender */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRReference $sender = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept> reasonCode Why is communication needed? */
		public array $reasonCode = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRReference> reasonReference Why is communication needed? */
		public array $reasonReference = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRAnnotation> note Comments made about communication request */
		public array $note = [],
	) {
		parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
	}
}
