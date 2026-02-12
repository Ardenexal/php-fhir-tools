<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\Goal;

/**
 * @description Indicates what should be done by when.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'Goal', elementPath: 'Goal.target', fhirVersion: 'R4')]
class GoalTarget extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept measure The parameter whose value is being tracked */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $measure = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Quantity|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Range|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string|bool|int|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Ratio detailX The target value to be achieved */
		public \Ardenexal\FHIRTools\Component\Models\R4\DataType\Quantity|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Range|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string|bool|int|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Ratio|null $detailX = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\DatePrimitive|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Duration dueX Reach goal on or before */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\DatePrimitive|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Duration|null $dueX = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
