<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

/**
 * @author Health Level Seven International (Orders and Observations)
 * @see http://hl7.org/fhir/StructureDefinition/DeviceRequest
 * @description Represents a request for a patient to employ a medical device. The device may be an implantable device, or an external assistive device, such as a walker.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource(type: 'DeviceRequest', version: '4.0.1', url: 'http://hl7.org/fhir/StructureDefinition/DeviceRequest', fhirVersion: 'R4')]
class DeviceRequestResource extends DomainResourceResource
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
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier> identifier External Request identifier */
		public array $identifier = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Primitive\CanonicalPrimitive> instantiatesCanonical Instantiates FHIR protocol or definition */
		public array $instantiatesCanonical = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive> instantiatesUri Instantiates external protocol or definition */
		public array $instantiatesUri = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference> basedOn What request fulfills */
		public array $basedOn = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference> priorRequest What request replaces */
		public array $priorRequest = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier groupIdentifier Identifier of composite request */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier $groupIdentifier = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\RequestStatusType status draft | active | on-hold | revoked | completed | entered-in-error | unknown */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\RequestStatusType $status = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\RequestIntentType intent proposal | plan | directive | order | original-order | reflex-order | filler-order | instance-order | option */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\RequestIntentType $intent = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\RequestPriorityType priority routine | urgent | asap | stat */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\RequestPriorityType $priority = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept codeX Device requested */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public \Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept|null $codeX = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\DeviceRequest\DeviceRequestParameter> parameter Device details */
		public array $parameter = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference subject Focus of request */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference $subject = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference encounter Encounter motivating request */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference $encounter = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Period|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Timing occurrenceX Desired time or schedule for use */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Period|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Timing|null $occurrenceX = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive authoredOn When recorded */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive $authoredOn = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference requester Who/what is requesting diagnostics */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference $requester = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept performerType Filler role */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $performerType = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference performer Requested Filler */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference $performer = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept> reasonCode Coded Reason for request */
		public array $reasonCode = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference> reasonReference Linked Reason for request */
		public array $reasonReference = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference> insurance Associated insurance coverage */
		public array $insurance = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference> supportingInfo Additional clinical information */
		public array $supportingInfo = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Annotation> note Notes or comments */
		public array $note = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference> relevantHistory Request provenance */
		public array $relevantHistory = [],
	) {
		parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
	}
}
