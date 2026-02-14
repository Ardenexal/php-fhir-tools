<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\Specimen;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Quantity;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;

/**
 * @description The container holding the specimen.  The recursive nature of containers; i.e. blood in tube in tray in rack is not addressed here.
 */
#[FHIRBackboneElement(parentResource: 'Specimen', elementPath: 'Specimen.container', fhirVersion: 'R4')]
class SpecimenContainer extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var array<Identifier> identifier Id for the container */
        public array $identifier = [],
        /** @var StringPrimitive|string|null description Textual description of the container */
        public StringPrimitive|string|null $description = null,
        /** @var CodeableConcept|null type Kind of container directly associated with specimen */
        public ?CodeableConcept $type = null,
        /** @var Quantity|null capacity Container volume or size */
        public ?Quantity $capacity = null,
        /** @var Quantity|null specimenQuantity Quantity of specimen within container */
        public ?Quantity $specimenQuantity = null,
        /** @var CodeableConcept|Reference|null additiveX Additive associated with container */
        public CodeableConcept|Reference|null $additiveX = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
