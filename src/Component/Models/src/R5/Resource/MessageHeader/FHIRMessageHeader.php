<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

/**
 * @author Health Level Seven International (Infrastructure And Messaging)
 * @see http://hl7.org/fhir/StructureDefinition/MessageHeader
 * @description The header for a message exchange that is either requesting or responding to an action.  The reference(s) that are the subject of the action as well as other information related to the action are typically transmitted in a bundle in which the MessageHeader resource instance is the first resource in the bundle.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource(type: 'MessageHeader', version: '5.0.0', url: 'http://hl7.org/fhir/StructureDefinition/MessageHeader', fhirVersion: 'R5')]
class FHIRMessageHeader extends FHIRDomainResource
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
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCoding|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCanonical eventX Event code or link to EventDefinition */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCoding|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCanonical|null $eventX = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRMessageHeaderDestination> destination Message destination application(s) */
		public array $destination = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference sender Real world sender of the message */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference $sender = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference author The source of the decision */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference $author = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRMessageHeaderSource source Message source application */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?FHIRMessageHeaderSource $source = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference responsible Final responsibility for event */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference $responsible = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept reason Cause of event */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept $reason = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRMessageHeaderResponse response If this is a reply to prior message */
		public ?FHIRMessageHeaderResponse $response = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference> focus The actual content of the message */
		public array $focus = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCanonical definition Link to the definition for this message */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCanonical $definition = null,
	) {
		parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
	}
}
