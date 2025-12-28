<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;

/**
 * @description A population criteria for the measure.
 */
#[FHIRBackboneElement(parentResource: 'Measure', elementPath: 'Measure.group.population', fhirVersion: 'R5')]
class FHIRMeasureGroupPopulation extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRString|string|null linkId Unique id for population in measure */
        public \FHIRString|string|null $linkId = null,
        /** @var FHIRCodeableConcept|null code initial-population | numerator | numerator-exclusion | denominator | denominator-exclusion | denominator-exception | measure-population | measure-population-exclusion | measure-observation */
        public ?\FHIRCodeableConcept $code = null,
        /** @var FHIRMarkdown|null description The human readable description of this population criteria */
        public ?\FHIRMarkdown $description = null,
        /** @var FHIRExpression|null criteria The criteria that defines this population */
        public ?\FHIRExpression $criteria = null,
        /** @var FHIRReference|null groupDefinition A group resource that defines this population */
        public ?\FHIRReference $groupDefinition = null,
        /** @var FHIRString|string|null inputPopulationId Which population */
        public \FHIRString|string|null $inputPopulationId = null,
        /** @var FHIRCodeableConcept|null aggregateMethod Aggregation method for a measure score (e.g. sum, average, median, minimum, maximum, count) */
        public ?\FHIRCodeableConcept $aggregateMethod = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
