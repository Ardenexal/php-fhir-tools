<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

/**
 * @fhir-backbone-element Questionnaire.item.answerOption
 * @description One of the permitted answers for a "choice" or "open-choice" question.
 */
class FHIRQuestionnaireItemAnswerOption extends \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRInteger|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRDate|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRTime|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCoding|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRReference valueX Answer value */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRInteger|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRDate|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRTime|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCoding|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRReference|null $valueX = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRBoolean initialSelected Whether option is selected by default */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRBoolean $initialSelected = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
