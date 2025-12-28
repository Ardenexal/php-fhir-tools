<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;

/**
 * @description The populations that make up the population group, one for each type of population appropriate for the measure.
 */
#[FHIRBackboneElement(parentResource: 'MeasureReport', elementPath: 'MeasureReport.group.population', fhirVersion: 'R4')]
class FHIRMeasureReportGroupPopulation extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRCodeableConcept|null code initial-population | numerator | numerator-exclusion | denominator | denominator-exclusion | denominator-exception | measure-population | measure-population-exclusion | measure-observation */
        public ?\FHIRCodeableConcept $code = null,
        /** @var FHIRInteger|null count Size of the population */
        public ?\FHIRInteger $count = null,
        /** @var FHIRReference|null subjectResults For subject-list reports, the subject results in this population */
        public ?\FHIRReference $subjectResults = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
