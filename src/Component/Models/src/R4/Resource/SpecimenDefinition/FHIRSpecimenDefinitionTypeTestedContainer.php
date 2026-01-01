<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRQuantity;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRString;

/**
 * @description The specimen's container.
 */
#[FHIRBackboneElement(parentResource: 'SpecimenDefinition', elementPath: 'SpecimenDefinition.typeTested.container', fhirVersion: 'R4')]
class FHIRSpecimenDefinitionTypeTestedContainer extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRCodeableConcept|null material Container material */
        public ?FHIRCodeableConcept $material = null,
        /** @var FHIRCodeableConcept|null type Kind of container associated with the kind of specimen */
        public ?FHIRCodeableConcept $type = null,
        /** @var FHIRCodeableConcept|null cap Color of container cap */
        public ?FHIRCodeableConcept $cap = null,
        /** @var FHIRString|string|null description Container description */
        public FHIRString|string|null $description = null,
        /** @var FHIRQuantity|null capacity Container capacity */
        public ?FHIRQuantity $capacity = null,
        /** @var FHIRQuantity|FHIRString|string|null minimumVolumeX Minimum volume */
        public FHIRQuantity|FHIRString|string|null $minimumVolumeX = null,
        /** @var array<FHIRSpecimenDefinitionTypeTestedContainerAdditive> additive Additive associated with container */
        public array $additive = [],
        /** @var FHIRString|string|null preparation Specimen container preparation */
        public FHIRString|string|null $preparation = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
