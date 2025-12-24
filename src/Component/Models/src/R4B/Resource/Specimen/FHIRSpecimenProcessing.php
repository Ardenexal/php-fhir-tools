<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRPeriod;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRReference;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRDateTime;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRString;

/**
 * @description Details concerning processing and processing steps for the specimen.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'Specimen', elementPath: 'Specimen.processing', fhirVersion: 'R4B')]
class FHIRSpecimenProcessing extends FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRString|string|null description Textual description of procedure */
        public FHIRString|string|null $description = null,
        /** @var FHIRCodeableConcept|null procedure Indicates the treatment step  applied to the specimen */
        public ?FHIRCodeableConcept $procedure = null,
        /** @var array<FHIRReference> additive Material used in the processing step */
        public array $additive = [],
        /** @var FHIRDateTime|FHIRPeriod|null timeX Date and time of specimen processing */
        public FHIRDateTime|FHIRPeriod|null $timeX = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
