<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-backbone-element QuestionnaireResponse.item
 * @description A group or question item from the original questionnaire for which answers are provided.
 */
class FHIRQuestionnaireResponseItem extends \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string linkId Pointer to specific item from Questionnaire */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string|null $linkId = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRUri definition ElementDefinition - details for the item */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRUri $definition = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string text Name for group or question text */
		public \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string|null $text = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRQuestionnaireResponseItemAnswer> answer The response(s) to the question */
		public array $answer = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRQuestionnaireResponseItem> item Child items of group item */
		public array $item = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
