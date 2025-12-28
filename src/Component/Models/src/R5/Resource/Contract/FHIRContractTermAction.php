<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

/**
 * @description An actor taking a role in an activity for which it can be assigned some degree of responsibility for the activity taking place.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'Contract', elementPath: 'Contract.term.action', fhirVersion: 'R5')]
class FHIRContractTermAction extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRBoolean doNotPerform True if the term prohibits the  action */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRBoolean $doNotPerform = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept type Type or form of the action */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept $type = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRContractTermActionSubject> subject Entity of the action */
		public array $subject = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept intent Purpose for the Contract Term Action */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept $intent = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string> linkId Pointer to specific item */
		public array $linkId = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept status State of the action */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept $status = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference context Episode associated with action */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference $context = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string> contextLinkId Pointer to specific item */
		public array $contextLinkId = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDateTime|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRPeriod|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRTiming occurrenceX When action happens */
		public \Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDateTime|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRPeriod|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRTiming|null $occurrenceX = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference> requester Who asked for action */
		public array $requester = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string> requesterLinkId Pointer to specific item */
		public array $requesterLinkId = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept> performerType Kind of service performer */
		public array $performerType = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept performerRole Competency of the performer */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept $performerRole = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference performer Actor that wil execute (or not) the action */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference $performer = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string> performerLinkId Pointer to specific item */
		public array $performerLinkId = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableReference> reason Why is action (not) needed? */
		public array $reason = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string> reasonLinkId Pointer to specific item */
		public array $reasonLinkId = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRAnnotation> note Comments about the action */
		public array $note = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRUnsignedInt> securityLabelNumber Action restriction numbers */
		public array $securityLabelNumber = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
