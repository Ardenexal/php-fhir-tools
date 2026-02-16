<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\MeasureReport;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference;

/**
 * @description The populations that make up the population group, one for each type of population appropriate for the measure.
 */
#[FHIRBackboneElement(parentResource: 'MeasureReport', elementPath: 'MeasureReport.group.population', fhirVersion: 'R4')]
class MeasureReportGroupPopulation extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var CodeableConcept|null code initial-population | numerator | numerator-exclusion | denominator | denominator-exclusion | denominator-exception | measure-population | measure-population-exclusion | measure-observation */
        public ?CodeableConcept $code = null,
        /** @var int|null count Size of the population */
        public ?int $count = null,
        /** @var Reference|null subjectResults For subject-list reports, the subject results in this population */
        public ?Reference $subjectResults = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
