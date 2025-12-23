<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

/**
 * @fhir-backbone-element PlanDefinition.goal.target
 * @description Indicates what should be done and within what timeframe.
 */
class FHIRPlanDefinitionGoalTarget extends \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCodeableConcept measure The parameter whose value is to be tracked */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCodeableConcept $measure = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRQuantity|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRRange|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCodeableConcept detailX The target value to be achieved */
		public \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRQuantity|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRRange|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCodeableConcept|null $detailX = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRDuration due Reach goal within */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRDuration $due = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
