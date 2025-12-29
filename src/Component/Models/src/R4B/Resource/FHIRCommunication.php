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
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRMeta meta Metadata about the resource */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRMeta $meta = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRUri implicitRules A set of rules under which this content was created */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRUri $implicitRules = null,
		/** @var null|string language Language of the resource content */
		public ?string $language = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRNarrative text Text summary of the resource, for human interpretation */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRNarrative $text = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRResource> contained Contained, inline Resources */
		public array $contained = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension> modifierExtension Extensions that cannot be ignored */
		public array $modifierExtension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRIdentifier> identifier Unique identifier */
		public array $identifier = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRCanonical> instantiatesCanonical Instantiates FHIR protocol or definition */
		public array $instantiatesCanonical = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRUri> instantiatesUri Instantiates external protocol or definition */
		public array $instantiatesUri = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRReference> basedOn Request fulfilled by this communication */
		public array $basedOn = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRReference> partOf Part of this action */
		public array $partOf = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRReference> inResponseTo Reply to */
		public array $inResponseTo = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIREventStatusType status preparation | in-progress | not-done | on-hold | stopped | completed | entered-in-error | unknown */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIREventStatusType $status = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCodeableConcept statusReason Reason for current status */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCodeableConcept $statusReason = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCodeableConcept> category Message category */
		public array $category = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRRequestPriorityType priority routine | urgent | asap | stat */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRRequestPriorityType $priority = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCodeableConcept> medium A channel of communication */
		public array $medium = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRReference subject Focus of message */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRReference $subject = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCodeableConcept topic Description of the purpose/content */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCodeableConcept $topic = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRReference> about Resources that pertain to this communication */
		public array $about = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRReference encounter Encounter created as part of */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRReference $encounter = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRDateTime sent When sent */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRDateTime $sent = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRDateTime received When received */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRDateTime $received = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRReference> recipient Message recipient */
		public array $recipient = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRReference sender Message sender */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRReference $sender = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCodeableConcept> reasonCode Indication for message */
		public array $reasonCode = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRReference> reasonReference Why was communication done? */
		public array $reasonReference = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCommunicationPayload> payload Message payload */
		public array $payload = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRAnnotation> note Comments made about the communication */
		public array $note = [],
	) {
		parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
	}
}
