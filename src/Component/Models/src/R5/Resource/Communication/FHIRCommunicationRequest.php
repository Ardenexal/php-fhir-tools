<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

/**
 * @author Health Level Seven International (Patient Care)
 * @see http://hl7.org/fhir/StructureDefinition/CommunicationRequest
 * @description A request to convey information; e.g. the CDS system proposes that an alert be sent to a responsible provider, the CDS system proposes that the public health agency be notified about a reportable condition.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource(
	type: 'CommunicationRequest',
	version: '5.0.0',
	url: 'http://hl7.org/fhir/StructureDefinition/CommunicationRequest',
	fhirVersion: 'R5',
)]
class FHIRCommunicationRequest extends FHIRDomainResource
{
	public function __construct(
		/** @var null|string id Logical id of this artifact */
		public ?string $id = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRMeta meta Metadata about the resource */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRMeta $meta = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRUri implicitRules A set of rules under which this content was created */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRUri $implicitRules = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRAllLanguagesType language Language of the resource content */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRAllLanguagesType $language = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRNarrative text Text summary of the resource, for human interpretation */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRNarrative $text = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRResource> contained Contained, inline Resources */
		public array $contained = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension> modifierExtension Extensions that cannot be ignored */
		public array $modifierExtension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRIdentifier> identifier Unique identifier */
		public array $identifier = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference> basedOn Fulfills plan or proposal */
		public array $basedOn = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference> replaces Request(s) replaced by this request */
		public array $replaces = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRIdentifier groupIdentifier Composite request this is part of */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRIdentifier $groupIdentifier = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRRequestStatusType status draft | active | on-hold | revoked | completed | entered-in-error | unknown */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRRequestStatusType $status = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept statusReason Reason for current status */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept $statusReason = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRRequestIntentType intent proposal | plan | directive | order | original-order | reflex-order | filler-order | instance-order | option */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRRequestIntentType $intent = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept> category Message category */
		public array $category = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRRequestPriorityType priority routine | urgent | asap | stat */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRRequestPriorityType $priority = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRBoolean doNotPerform True if request is prohibiting action */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRBoolean $doNotPerform = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept> medium A channel of communication */
		public array $medium = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference subject Focus of message */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference $subject = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference> about Resources that pertain to this communication request */
		public array $about = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference encounter The Encounter during which this CommunicationRequest was created */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference $encounter = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCommunicationRequestPayload> payload Message payload */
		public array $payload = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDateTime|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRPeriod occurrenceX When scheduled */
		public \Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDateTime|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRPeriod|null $occurrenceX = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDateTime authoredOn When request transitioned to being actionable */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDateTime $authoredOn = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference requester Who asks for the information to be shared */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference $requester = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference> recipient Who to share the information with */
		public array $recipient = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference> informationProvider Who should share the information */
		public array $informationProvider = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableReference> reason Why is communication needed? */
		public array $reason = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRAnnotation> note Comments made about communication request */
		public array $note = [],
	) {
		parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
	}
}
