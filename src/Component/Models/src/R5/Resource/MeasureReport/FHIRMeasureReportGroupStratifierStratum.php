<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRDuration;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRPeriod;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRQuantity;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRRange;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRBoolean;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDateTime;

/**
 * @description This element contains the results for a single stratum within the stratifier. For example, when stratifying on administrative gender, there will be four strata, one for each possible gender value.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'MeasureReport', elementPath: 'MeasureReport.group.stratifier.stratum', fhirVersion: 'R5')]
class FHIRMeasureReportGroupStratifierStratum extends FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRCodeableConcept|FHIRBoolean|FHIRQuantity|FHIRRange|FHIRReference|null valueX The stratum value, e.g. male */
        public FHIRCodeableConcept|FHIRBoolean|FHIRQuantity|FHIRRange|FHIRReference|null $valueX = null,
        /** @var array<FHIRMeasureReportGroupStratifierStratumComponent> component Stratifier component values */
        public array $component = [],
        /** @var array<FHIRMeasureReportGroupStratifierStratumPopulation> population Population results in this stratum */
        public array $population = [],
        /** @var FHIRQuantity|FHIRDateTime|FHIRCodeableConcept|FHIRPeriod|FHIRRange|FHIRDuration|null measureScoreX What score this stratum achieved */
        public FHIRQuantity|FHIRDateTime|FHIRCodeableConcept|FHIRPeriod|FHIRRange|FHIRDuration|null $measureScoreX = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
