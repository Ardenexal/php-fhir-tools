<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\MeasureReport;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;

/**
 * @description When a measure includes multiple stratifiers, there will be a stratifier group for each stratifier defined by the measure.
 */
#[FHIRBackboneElement(parentResource: 'MeasureReport', elementPath: 'MeasureReport.group.stratifier', fhirVersion: 'R4')]
class MeasureReportGroupStratifier extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var array<CodeableConcept> code What stratifier of the group */
        public array $code = [],
        /** @var array<MeasureReportGroupStratifierStratum> stratum Stratum results, one for each unique value, or set of values, in the stratifier, or stratifier components */
        public array $stratum = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
