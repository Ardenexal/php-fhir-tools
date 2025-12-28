<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;

/**
 * @description Details concerning processing and processing steps for the specimen.
 */
#[FHIRBackboneElement(parentResource: 'Specimen', elementPath: 'Specimen.processing', fhirVersion: 'R5')]
class FHIRSpecimenProcessing extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRString|string|null description Textual description of procedure */
        public \FHIRString|string|null $description = null,
        /** @var FHIRCodeableConcept|null method Indicates the treatment step  applied to the specimen */
        public ?\FHIRCodeableConcept $method = null,
        /** @var array<FHIRReference> additive Material used in the processing step */
        public array $additive = [],
        /** @var FHIRDateTime|FHIRPeriod|null timeX Date and time of specimen processing */
        public \FHIRDateTime|\FHIRPeriod|null $timeX = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
