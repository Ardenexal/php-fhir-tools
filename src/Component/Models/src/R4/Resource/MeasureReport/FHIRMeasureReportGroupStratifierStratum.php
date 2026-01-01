<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRQuantity;

/**
 * @description This element contains the results for a single stratum within the stratifier. For example, when stratifying on administrative gender, there will be four strata, one for each possible gender value.
 */
#[FHIRBackboneElement(parentResource: 'MeasureReport', elementPath: 'MeasureReport.group.stratifier.stratum', fhirVersion: 'R4')]
class FHIRMeasureReportGroupStratifierStratum extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRCodeableConcept|null value The stratum value, e.g. male */
        public ?FHIRCodeableConcept $value = null,
        /** @var array<FHIRMeasureReportGroupStratifierStratumComponent> component Stratifier component values */
        public array $component = [],
        /** @var array<FHIRMeasureReportGroupStratifierStratumPopulation> population Population results in this stratum */
        public array $population = [],
        /** @var FHIRQuantity|null measureScore What score this stratum achieved */
        public ?FHIRQuantity $measureScore = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
