<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRQuantity;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRMarkdown;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString;

/**
 * @description The specimen's container.
 */
#[FHIRBackboneElement(parentResource: 'SpecimenDefinition', elementPath: 'SpecimenDefinition.typeTested.container', fhirVersion: 'R5')]
class FHIRSpecimenDefinitionTypeTestedContainer extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRCodeableConcept|null material The material type used for the container */
        public ?FHIRCodeableConcept $material = null,
        /** @var FHIRCodeableConcept|null type Kind of container associated with the kind of specimen */
        public ?FHIRCodeableConcept $type = null,
        /** @var FHIRCodeableConcept|null cap Color of container cap */
        public ?FHIRCodeableConcept $cap = null,
        /** @var FHIRMarkdown|null description The description of the kind of container */
        public ?FHIRMarkdown $description = null,
        /** @var FHIRQuantity|null capacity The capacity of this kind of container */
        public ?FHIRQuantity $capacity = null,
        /** @var FHIRQuantity|FHIRString|string|null minimumVolumeX Minimum volume */
        public FHIRQuantity|FHIRString|string|null $minimumVolumeX = null,
        /** @var array<FHIRSpecimenDefinitionTypeTestedContainerAdditive> additive Additive associated with container */
        public array $additive = [],
        /** @var FHIRMarkdown|null preparation Special processing applied to the container for this specimen type */
        public ?FHIRMarkdown $preparation = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
