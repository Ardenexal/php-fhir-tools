<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description A stratifier component value.
 */
#[FHIRBackboneElement(parentResource: 'MeasureReport', elementPath: 'MeasureReport.group.stratifier.stratum.component', fhirVersion: 'R4')]
class FHIRMeasureReportGroupStratifierStratumComponent extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRCodeableConcept|null code What stratifier component of the group */
        #[NotBlank]
        public ?\FHIRCodeableConcept $code = null,
        /** @var FHIRCodeableConcept|null value The stratum component value, e.g. male */
        #[NotBlank]
        public ?\FHIRCodeableConcept $value = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
