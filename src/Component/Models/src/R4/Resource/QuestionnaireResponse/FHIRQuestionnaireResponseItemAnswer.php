<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

/**
 * @description The respondent's answer(s) to the question.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'QuestionnaireResponse', elementPath: 'QuestionnaireResponse.item.answer', fhirVersion: 'R4')]
class FHIRQuestionnaireResponseItemAnswer extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRBoolean|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRDecimal|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRInteger|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRDate|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRDateTime|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRTime|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRString|string|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRUri|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRAttachment|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCoding|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRQuantity|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRReference valueX Single-valued answer to the question */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRBoolean|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRDecimal|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRInteger|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRDate|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRDateTime|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRTime|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRString|string|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRUri|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRAttachment|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCoding|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRQuantity|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRReference|null $valueX = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRQuestionnaireResponseItem> item Nested groups and questions */
		public array $item = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
