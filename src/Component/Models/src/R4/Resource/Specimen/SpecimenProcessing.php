<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\Specimen;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Period;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;

/**
 * @description Details concerning processing and processing steps for the specimen.
 */
#[FHIRBackboneElement(parentResource: 'Specimen', elementPath: 'Specimen.processing', fhirVersion: 'R4')]
class SpecimenProcessing extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var StringPrimitive|string|null description Textual description of procedure */
        public StringPrimitive|string|null $description = null,
        /** @var CodeableConcept|null procedure Indicates the treatment step  applied to the specimen */
        public ?CodeableConcept $procedure = null,
        /** @var array<Reference> additive Material used in the processing step */
        public array $additive = [],
        /** @var DateTimePrimitive|Period|null timeX Date and time of specimen processing */
        public DateTimePrimitive|Period|null $timeX = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
