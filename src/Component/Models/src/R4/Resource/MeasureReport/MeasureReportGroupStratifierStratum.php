<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\MeasureReport;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Quantity;

/**
 * @description This element contains the results for a single stratum within the stratifier. For example, when stratifying on administrative gender, there will be four strata, one for each possible gender value.
 */
#[FHIRBackboneElement(parentResource: 'MeasureReport', elementPath: 'MeasureReport.group.stratifier.stratum', fhirVersion: 'R4')]
class MeasureReportGroupStratifierStratum extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var CodeableConcept|null value The stratum value, e.g. male */
        public ?CodeableConcept $value = null,
        /** @var array<MeasureReportGroupStratifierStratumComponent> component Stratifier component values */
        public array $component = [],
        /** @var array<MeasureReportGroupStratifierStratumPopulation> population Population results in this stratum */
        public array $population = [],
        /** @var Quantity|null measureScore What score this stratum achieved */
        public ?Quantity $measureScore = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
