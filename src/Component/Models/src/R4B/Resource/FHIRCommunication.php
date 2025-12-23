<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

/**
 * @author Health Level Seven International (Patient Care)
 * @see http://hl7.org/fhir/StructureDefinition/Communication
 * @description An occurrence of information being transmitted; e.g. an alert that was sent to a responsible provider, a public health agency that was notified about a reportable condition.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource(
	type: 'Communication',
	version: '4.3.0',
	url: 'http://hl7.org/fhir/StructureDefinition/Communication',
	fhirVersion: 'R4B',
)]
class FHIRCommunication extends FHIRDomainResource
{
	public function __construct(
		/** @var null|string id Logical id of this artifact */
		public ?string $id = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRMeta meta Metadata about the resource */
		public ?FHIRMeta $meta = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRUri implicitRules A set of rules under which this content was created */
		public ?FHIRUri $implicitRules = null,
		/** @var null|string language Language of the resource content */
		public ?string $language = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRNarrative text Text summary of the resource, for human interpretation */
		public ?FHIRNarrative $text = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRResource> contained Contained, inline Resources */
		public array $contained = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored */
		public array $modifierExtension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRIdentifier> identifier Unique identifier */
		public array $identifier = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCanonical> instantiatesCanonical Instantiates FHIR protocol or definition */
		public array $instantiatesCanonical = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRUri> instantiatesUri Instantiates external protocol or definition */
		public array $instantiatesUri = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRReference> basedOn Request fulfilled by this communication */
		public array $basedOn = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRReference> partOf Part of this action */
		public array $partOf = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRReference> inResponseTo Reply to */
		public array $inResponseTo = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIREventStatusType status preparation | in-progress | not-done | on-hold | stopped | completed | entered-in-error | unknown */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?FHIREventStatusType $status = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCodeableConcept statusReason Reason for current status */
		public ?FHIRCodeableConcept $statusReason = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCodeableConcept> category Message category */
		public array $category = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRRequestPriorityType priority routine | urgent | asap | stat */
		public ?FHIRRequestPriorityType $priority = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCodeableConcept> medium A channel of communication */
		public array $medium = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRReference subject Focus of message */
		public ?FHIRReference $subject = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCodeableConcept topic Description of the purpose/content */
		public ?FHIRCodeableConcept $topic = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRReference> about Resources that pertain to this communication */
		public array $about = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRReference encounter Encounter created as part of */
		public ?FHIRReference $encounter = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRDateTime sent When sent */
		public ?FHIRDateTime $sent = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRDateTime received When received */
		public ?FHIRDateTime $received = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRReference> recipient Message recipient */
		public array $recipient = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRReference sender Message sender */
		public ?FHIRReference $sender = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCodeableConcept> reasonCode Indication for message */
		public array $reasonCode = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRReference> reasonReference Why was communication done? */
		public array $reasonReference = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCommunicationPayload> payload Message payload */
		public array $payload = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRAnnotation> note Comments made about the communication */
		public array $note = [],
	) {
		parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
	}
}
