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
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDateTime;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString;

/**
 * @description The results of the calculation, one for each population group in the measure.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'MeasureReport', elementPath: 'MeasureReport.group', fhirVersion: 'R5')]
class FHIRMeasureReportGroup extends FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRString|string|null linkId Pointer to specific group from Measure */
        public FHIRString|string|null $linkId = null,
        /** @var FHIRCodeableConcept|null code Meaning of the group */
        public ?FHIRCodeableConcept $code = null,
        /** @var FHIRReference|null subject What individual(s) the report is for */
        public ?FHIRReference $subject = null,
        /** @var array<FHIRMeasureReportGroupPopulation> population The populations in the group */
        public array $population = [],
        /** @var FHIRQuantity|FHIRDateTime|FHIRCodeableConcept|FHIRPeriod|FHIRRange|FHIRDuration|null measureScoreX What score this group achieved */
        public FHIRQuantity|FHIRDateTime|FHIRCodeableConcept|FHIRPeriod|FHIRRange|FHIRDuration|null $measureScoreX = null,
        /** @var array<FHIRMeasureReportGroupStratifier> stratifier Stratification results */
        public array $stratifier = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
