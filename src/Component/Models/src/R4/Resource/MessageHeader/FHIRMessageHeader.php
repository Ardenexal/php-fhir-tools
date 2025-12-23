<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

/**
 * @author Health Level Seven International (Infrastructure And Messaging)
 * @see http://hl7.org/fhir/StructureDefinition/MessageHeader
 * @description The header for a message exchange that is either requesting or responding to an action.  The reference(s) that are the subject of the action as well as other information related to the action are typically transmitted in a bundle in which the MessageHeader resource instance is the first resource in the bundle.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource(type: 'MessageHeader', version: '4.0.1', url: 'http://hl7.org/fhir/StructureDefinition/MessageHeader', fhirVersion: 'R4')]
class FHIRMessageHeader extends FHIRDomainResource
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
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCoding|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRUri eventX Code for the event this message represents or link to event definition */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public FHIRCoding|FHIRUri|null $eventX = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRMessageHeaderDestination> destination Message destination application(s) */
		public array $destination = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRReference sender Real world sender of the message */
		public ?FHIRReference $sender = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRReference enterer The source of the data entry */
		public ?FHIRReference $enterer = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRReference author The source of the decision */
		public ?FHIRReference $author = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRMessageHeaderSource source Message source application */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?FHIRMessageHeaderSource $source = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRReference responsible Final responsibility for event */
		public ?FHIRReference $responsible = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCodeableConcept reason Cause of event */
		public ?FHIRCodeableConcept $reason = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRMessageHeaderResponse response If this is a reply to prior message */
		public ?FHIRMessageHeaderResponse $response = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRReference> focus The actual content of the message */
		public array $focus = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCanonical definition Link to the definition for this message */
		public ?FHIRCanonical $definition = null,
	) {
		parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
	}
}
