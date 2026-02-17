<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\Specimen;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Duration;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Period;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Quantity;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive;

/**
 * @description Details concerning the specimen collection.
 */
#[FHIRBackboneElement(parentResource: 'Specimen', elementPath: 'Specimen.collection', fhirVersion: 'R4')]
class SpecimenCollection extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var Reference|null collector Who collected the specimen */
        public ?Reference $collector = null,
        /** @var DateTimePrimitive|Period|null collectedX Collection time */
        public DateTimePrimitive|Period|null $collectedX = null,
        /** @var Duration|null duration How long it took to collect specimen */
        public ?Duration $duration = null,
        /** @var Quantity|null quantity The quantity of specimen collected */
        public ?Quantity $quantity = null,
        /** @var CodeableConcept|null method Technique used to perform collection */
        public ?CodeableConcept $method = null,
        /** @var CodeableConcept|null bodySite Anatomical collection site */
        public ?CodeableConcept $bodySite = null,
        /** @var CodeableConcept|Duration|null fastingStatusX Whether or how long patient abstained from food and/or drink */
        public CodeableConcept|Duration|null $fastingStatusX = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
