<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRIdentifier;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference;

/**
 * @description Inputs for the analysis event.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'GenomicStudy', elementPath: 'GenomicStudy.analysis.input', fhirVersion: 'R5')]
class FHIRGenomicStudyAnalysisInput extends FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRReference|null file File containing input data */
        public ?FHIRReference $file = null,
        /** @var FHIRCodeableConcept|null type Type of input data (e.g., BAM, CRAM, or FASTA) */
        public ?FHIRCodeableConcept $type = null,
        /** @var FHIRIdentifier|FHIRReference|null generatedByX The analysis event or other GenomicStudy that generated this input file */
        public FHIRIdentifier|FHIRReference|null $generatedByX = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
