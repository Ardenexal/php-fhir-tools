<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

/**
 * @fhir-backbone-element Questionnaire.item
 * @description A particular question, question grouping or display text that is part of the questionnaire.
 */
class FHIRQuestionnaireItem extends \Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRString|string linkId Unique id for item in questionnaire */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public \Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRString|string|null $linkId = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRUri definition ElementDefinition - details for the item */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRUri $definition = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCoding> code Corresponding concept for this item in a terminology */
		public array $code = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRString|string prefix E.g. "1(a)", "2.5.3" */
		public \Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRString|string|null $prefix = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRString|string text Primary text for the item */
		public \Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRString|string|null $text = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRQuestionnaireItemTypeType type group | display | boolean | decimal | integer | date | dateTime + */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRQuestionnaireItemTypeType $type = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRQuestionnaireItemEnableWhen> enableWhen Only allow data when */
		public array $enableWhen = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIREnableWhenBehaviorType enableBehavior all | any */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIREnableWhenBehaviorType $enableBehavior = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRBoolean required Whether the item must be included in data results */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRBoolean $required = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRBoolean repeats Whether the item may repeat */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRBoolean $repeats = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRBoolean readOnly Don't allow human editing */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRBoolean $readOnly = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRInteger maxLength No more than this many characters */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRInteger $maxLength = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCanonical answerValueSet Valueset containing permitted answers */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCanonical $answerValueSet = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRQuestionnaireItemAnswerOption> answerOption Permitted answer */
		public array $answerOption = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRQuestionnaireItemInitial> initial Initial value(s) when item is first rendered */
		public array $initial = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRQuestionnaireItem> item Nested questionnaire items */
		public array $item = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
