<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

/**
 * @fhir-backbone-element QuestionnaireResponse.item.answer
 * @description The respondent's answer(s) to the question.
 */
class FHIRQuestionnaireResponseItemAnswer extends \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRBoolean|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRDecimal|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRInteger|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRDate|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRDateTime|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRTime|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRUri|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRAttachment|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCoding|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRQuantity|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRReference valueX Single-valued answer to the question */
		public \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRBoolean|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRDecimal|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRInteger|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRDate|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRDateTime|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRTime|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRUri|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRAttachment|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCoding|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRQuantity|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRReference|null $valueX = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRQuestionnaireResponseItem> item Nested groups and questions */
		public array $item = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
