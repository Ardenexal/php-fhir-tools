<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\Questionnaire;

/**
 * @description One of the permitted answers for a "choice" or "open-choice" question.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'Questionnaire', elementPath: 'Questionnaire.item.answerOption', fhirVersion: 'R4')]
class QuestionnaireItemAnswerOption extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|int|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\DatePrimitive|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\TimePrimitive|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Coding|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference valueX Answer value */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public int|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\DatePrimitive|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\TimePrimitive|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Coding|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference|null $valueX = null,
		/** @var null|bool initialSelected Whether option is selected by default */
		public ?bool $initialSelected = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
