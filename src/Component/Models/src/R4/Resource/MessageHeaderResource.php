<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

/**
 * @author Health Level Seven International (Infrastructure And Messaging)
 * @see http://hl7.org/fhir/StructureDefinition/MessageHeader
 * @description The header for a message exchange that is either requesting or responding to an action.  The reference(s) that are the subject of the action as well as other information related to the action are typically transmitted in a bundle in which the MessageHeader resource instance is the first resource in the bundle.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource(type: 'MessageHeader', version: '4.0.1', url: 'http://hl7.org/fhir/StructureDefinition/MessageHeader', fhirVersion: 'R4')]
class MessageHeaderResource extends DomainResourceResource
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
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Coding|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive eventX Code for the event this message represents or link to event definition */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public \Ardenexal\FHIRTools\Component\Models\R4\DataType\Coding|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive|null $eventX = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\MessageHeader\MessageHeaderDestination> destination Message destination application(s) */
		public array $destination = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference sender Real world sender of the message */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference $sender = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference enterer The source of the data entry */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference $enterer = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference author The source of the decision */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference $author = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\MessageHeader\MessageHeaderSource source Message source application */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?MessageHeader\MessageHeaderSource $source = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference responsible Final responsibility for event */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference $responsible = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept reason Cause of event */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $reason = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\MessageHeader\MessageHeaderResponse response If this is a reply to prior message */
		public ?MessageHeader\MessageHeaderResponse $response = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference> focus The actual content of the message */
		public array $focus = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\CanonicalPrimitive definition Link to the definition for this message */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\CanonicalPrimitive $definition = null,
	) {
		parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
	}
}
