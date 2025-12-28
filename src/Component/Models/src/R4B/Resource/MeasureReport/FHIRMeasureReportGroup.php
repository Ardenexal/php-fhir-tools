<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRQuantity;

/**
 * @description The results of the calculation, one for each population group in the measure.
 */
#[FHIRBackboneElement(parentResource: 'MeasureReport', elementPath: 'MeasureReport.group', fhirVersion: 'R4B')]
class FHIRMeasureReportGroup extends \Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRCodeableConcept|null code Meaning of the group */
        public ?FHIRCodeableConcept $code = null,
        /** @var array<FHIRMeasureReportGroupPopulation> population The populations in the group */
        public array $population = [],
        /** @var FHIRQuantity|null measureScore What score this group achieved */
        public ?FHIRQuantity $measureScore = null,
        /** @var array<FHIRMeasureReportGroupStratifier> stratifier Stratification results */
        public array $stratifier = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
