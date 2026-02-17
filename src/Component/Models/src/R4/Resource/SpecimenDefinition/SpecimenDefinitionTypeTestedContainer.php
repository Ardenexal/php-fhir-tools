<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\SpecimenDefinition;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Quantity;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;

/**
 * @description The specimen's container.
 */
#[FHIRBackboneElement(parentResource: 'SpecimenDefinition', elementPath: 'SpecimenDefinition.typeTested.container', fhirVersion: 'R4')]
class SpecimenDefinitionTypeTestedContainer extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var CodeableConcept|null material Container material */
        public ?CodeableConcept $material = null,
        /** @var CodeableConcept|null type Kind of container associated with the kind of specimen */
        public ?CodeableConcept $type = null,
        /** @var CodeableConcept|null cap Color of container cap */
        public ?CodeableConcept $cap = null,
        /** @var StringPrimitive|string|null description Container description */
        public StringPrimitive|string|null $description = null,
        /** @var Quantity|null capacity Container capacity */
        public ?Quantity $capacity = null,
        /** @var Quantity|StringPrimitive|string|null minimumVolumeX Minimum volume */
        public Quantity|StringPrimitive|string|null $minimumVolumeX = null,
        /** @var array<SpecimenDefinitionTypeTestedContainerAdditive> additive Additive associated with container */
        public array $additive = [],
        /** @var StringPrimitive|string|null preparation Specimen container preparation */
        public StringPrimitive|string|null $preparation = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
