<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

/**
 * @description Indicates what should be done and within what timeframe.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'PlanDefinition', elementPath: 'PlanDefinition.goal.target', fhirVersion: 'R4')]
class FHIRPlanDefinitionGoalTarget extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept measure The parameter whose value is to be tracked */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept $measure = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRQuantity|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRRange|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept detailX The target value to be achieved */
		public \Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRQuantity|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRRange|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept|null $detailX = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRDuration due Reach goal within */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRDuration $due = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
