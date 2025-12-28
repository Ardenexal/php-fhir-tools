<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

/**
 * @description An actor taking a role in an activity for which it can be assigned some degree of responsibility for the activity taking place.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'Contract', elementPath: 'Contract.term.action', fhirVersion: 'R4B')]
class FHIRContractTermAction extends \Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRBoolean doNotPerform True if the term prohibits the  action */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRBoolean $doNotPerform = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCodeableConcept type Type or form of the action */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCodeableConcept $type = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRContractTermActionSubject> subject Entity of the action */
		public array $subject = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCodeableConcept intent Purpose for the Contract Term Action */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCodeableConcept $intent = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRString|string> linkId Pointer to specific item */
		public array $linkId = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCodeableConcept status State of the action */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCodeableConcept $status = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRReference context Episode associated with action */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRReference $context = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRString|string> contextLinkId Pointer to specific item */
		public array $contextLinkId = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRDateTime|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRPeriod|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRTiming occurrenceX When action happens */
		public \Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRDateTime|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRPeriod|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRTiming|null $occurrenceX = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRReference> requester Who asked for action */
		public array $requester = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRString|string> requesterLinkId Pointer to specific item */
		public array $requesterLinkId = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCodeableConcept> performerType Kind of service performer */
		public array $performerType = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCodeableConcept performerRole Competency of the performer */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCodeableConcept $performerRole = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRReference performer Actor that wil execute (or not) the action */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRReference $performer = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRString|string> performerLinkId Pointer to specific item */
		public array $performerLinkId = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCodeableConcept> reasonCode Why is action (not) needed? */
		public array $reasonCode = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRReference> reasonReference Why is action (not) needed? */
		public array $reasonReference = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRString|string> reason Why action is to be performed */
		public array $reason = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRString|string> reasonLinkId Pointer to specific item */
		public array $reasonLinkId = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRAnnotation> note Comments about the action */
		public array $note = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRUnsignedInt> securityLabelNumber Action restriction numbers */
		public array $securityLabelNumber = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
