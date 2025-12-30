<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRIdentifier;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRQuantity;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRReference;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRString;

/**
 * @description The container holding the specimen.  The recursive nature of containers; i.e. blood in tube in tray in rack is not addressed here.
 */
#[FHIRBackboneElement(parentResource: 'Specimen', elementPath: 'Specimen.container', fhirVersion: 'R4B')]
class FHIRSpecimenContainer extends \Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var array<FHIRIdentifier> identifier Id for the container */
        public array $identifier = [],
        /** @var FHIRString|string|null description Textual description of the container */
        public FHIRString|string|null $description = null,
        /** @var FHIRCodeableConcept|null type Kind of container directly associated with specimen */
        public ?FHIRCodeableConcept $type = null,
        /** @var FHIRQuantity|null capacity Container volume or size */
        public ?FHIRQuantity $capacity = null,
        /** @var FHIRQuantity|null specimenQuantity Quantity of specimen within container */
        public ?FHIRQuantity $specimenQuantity = null,
        /** @var FHIRCodeableConcept|FHIRReference|null additiveX Additive associated with container */
        public FHIRCodeableConcept|FHIRReference|null $additiveX = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
