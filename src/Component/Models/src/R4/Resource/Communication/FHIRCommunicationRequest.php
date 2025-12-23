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
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRIdentifier> identifier Unique identifier */
		public array $identifier = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRReference> basedOn Fulfills plan or proposal */
		public array $basedOn = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRReference> replaces Request(s) replaced by this request */
		public array $replaces = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRIdentifier groupIdentifier Composite request this is part of */
		public ?FHIRIdentifier $groupIdentifier = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRRequestStatusType status draft | active | on-hold | revoked | completed | entered-in-error | unknown */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?FHIRRequestStatusType $status = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCodeableConcept statusReason Reason for current status */
		public ?FHIRCodeableConcept $statusReason = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCodeableConcept> category Message category */
		public array $category = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRRequestPriorityType priority routine | urgent | asap | stat */
		public ?FHIRRequestPriorityType $priority = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRBoolean doNotPerform True if request is prohibiting action */
		public ?FHIRBoolean $doNotPerform = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCodeableConcept> medium A channel of communication */
		public array $medium = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRReference subject Focus of message */
		public ?FHIRReference $subject = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRReference> about Resources that pertain to this communication request */
		public array $about = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRReference encounter Encounter created as part of */
		public ?FHIRReference $encounter = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCommunicationRequestPayload> payload Message payload */
		public array $payload = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRDateTime|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRPeriod occurrenceX When scheduled */
		public FHIRDateTime|FHIRPeriod|null $occurrenceX = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRDateTime authoredOn When request transitioned to being actionable */
		public ?FHIRDateTime $authoredOn = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRReference requester Who/what is requesting service */
		public ?FHIRReference $requester = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRReference> recipient Message recipient */
		public array $recipient = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRReference sender Message sender */
		public ?FHIRReference $sender = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCodeableConcept> reasonCode Why is communication needed? */
		public array $reasonCode = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRReference> reasonReference Why is communication needed? */
		public array $reasonReference = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRAnnotation> note Comments made about communication request */
		public array $note = [],
	) {
		parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
	}
}
