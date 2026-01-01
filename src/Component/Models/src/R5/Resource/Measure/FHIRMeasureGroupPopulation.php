<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

/**
 * @description A population criteria for the measure.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'Measure', elementPath: 'Measure.group.population', fhirVersion: 'R5')]
class FHIRMeasureGroupPopulation extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string linkId Unique id for population in measure */
		public \Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string|null $linkId = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept code initial-population | numerator | numerator-exclusion | denominator | denominator-exclusion | denominator-exception | measure-population | measure-population-exclusion | measure-observation */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept $code = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRMarkdown description The human readable description of this population criteria */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRMarkdown $description = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExpression criteria The criteria that defines this population */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExpression $criteria = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference groupDefinition A group resource that defines this population */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference $groupDefinition = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string inputPopulationId Which population */
		public \Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string|null $inputPopulationId = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept aggregateMethod Aggregation method for a measure score (e.g. sum, average, median, minimum, maximum, count) */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept $aggregateMethod = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
