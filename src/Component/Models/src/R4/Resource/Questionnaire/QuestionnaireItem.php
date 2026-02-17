<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\Questionnaire;

/**
 * @description A particular question, question grouping or display text that is part of the questionnaire.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'Questionnaire', elementPath: 'Questionnaire.item', fhirVersion: 'R4')]
class QuestionnaireItem extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string linkId Unique id for item in questionnaire */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string|null $linkId = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive definition ElementDefinition - details for the item */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive $definition = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Coding> code Corresponding concept for this item in a terminology */
		public array $code = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string prefix E.g. "1(a)", "2.5.3" */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string|null $prefix = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string text Primary text for the item */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string|null $text = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\QuestionnaireItemTypeType type group | display | boolean | decimal | integer | date | dateTime + */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\QuestionnaireItemTypeType $type = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\Questionnaire\QuestionnaireItemEnableWhen> enableWhen Only allow data when */
		public array $enableWhen = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\EnableWhenBehaviorType enableBehavior all | any */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\EnableWhenBehaviorType $enableBehavior = null,
		/** @var null|bool required Whether the item must be included in data results */
		public ?bool $required = null,
		/** @var null|bool repeats Whether the item may repeat */
		public ?bool $repeats = null,
		/** @var null|bool readOnly Don't allow human editing */
		public ?bool $readOnly = null,
		/** @var null|int maxLength No more than this many characters */
		public ?int $maxLength = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\CanonicalPrimitive answerValueSet Valueset containing permitted answers */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\CanonicalPrimitive $answerValueSet = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\Questionnaire\QuestionnaireItemAnswerOption> answerOption Permitted answer */
		public array $answerOption = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\Questionnaire\QuestionnaireItemInitial> initial Initial value(s) when item is first rendered */
		public array $initial = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\Questionnaire\QuestionnaireItem> item Nested questionnaire items */
		public array $item = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
