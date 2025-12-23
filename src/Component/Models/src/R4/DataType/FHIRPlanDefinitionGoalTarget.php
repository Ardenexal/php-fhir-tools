<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

/**
 * @fhir-backbone-element PlanDefinition.goal.target
 * @description Indicates what should be done and within what timeframe.
 */
class FHIRPlanDefinitionGoalTarget extends \Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCodeableConcept measure The parameter whose value is to be tracked */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCodeableConcept $measure = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRQuantity|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRRange|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCodeableConcept detailX The target value to be achieved */
		public \Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRQuantity|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRRange|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCodeableConcept|null $detailX = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRDuration due Reach goal within */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRDuration $due = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
