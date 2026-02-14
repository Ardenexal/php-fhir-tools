<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\MeasureReport;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Quantity;

/**
 * @description The results of the calculation, one for each population group in the measure.
 */
#[FHIRBackboneElement(parentResource: 'MeasureReport', elementPath: 'MeasureReport.group', fhirVersion: 'R4')]
class MeasureReportGroup extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var CodeableConcept|null code Meaning of the group */
        public ?CodeableConcept $code = null,
        /** @var array<MeasureReportGroupPopulation> population The populations in the group */
        public array $population = [],
        /** @var Quantity|null measureScore What score this group achieved */
        public ?Quantity $measureScore = null,
        /** @var array<MeasureReportGroupStratifier> stratifier Stratification results */
        public array $stratifier = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
