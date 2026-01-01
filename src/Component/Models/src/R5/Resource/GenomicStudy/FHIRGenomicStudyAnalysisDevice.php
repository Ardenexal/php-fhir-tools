<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference;

/**
 * @description Devices used for the analysis (e.g., instruments, software), with settings and parameters.
 */
#[FHIRBackboneElement(parentResource: 'GenomicStudy', elementPath: 'GenomicStudy.analysis.device', fhirVersion: 'R5')]
class FHIRGenomicStudyAnalysisDevice extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRReference|null device Device used for the analysis */
        public ?FHIRReference $device = null,
        /** @var FHIRCodeableConcept|null function Specific function for the device used for the analysis */
        public ?FHIRCodeableConcept $function = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
