<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;

/**
 * @description The populations that make up the stratum, one for each type of population appropriate to the measure.
 */
#[FHIRBackboneElement(parentResource: 'MeasureReport', elementPath: 'MeasureReport.group.stratifier.stratum.population', fhirVersion: 'R5')]
class FHIRMeasureReportGroupStratifierStratumPopulation extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRString|string|null linkId Pointer to specific population from Measure */
        public \FHIRString|string|null $linkId = null,
        /** @var FHIRCodeableConcept|null code initial-population | numerator | numerator-exclusion | denominator | denominator-exclusion | denominator-exception | measure-population | measure-population-exclusion | measure-observation */
        public ?\FHIRCodeableConcept $code = null,
        /** @var FHIRInteger|null count Size of the population */
        public ?\FHIRInteger $count = null,
        /** @var FHIRReference|null subjectResults For subject-list reports, the subject results in this population */
        public ?\FHIRReference $subjectResults = null,
        /** @var array<FHIRReference> subjectReport For subject-list reports, a subject result in this population */
        public array $subjectReport = [],
        /** @var FHIRReference|null subjects What individual(s) in the population */
        public ?\FHIRReference $subjects = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
