<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Physical parts of the manufactured item, that it is intrisically made from. This is distinct from the ingredients that are part of its chemical makeup.
 */
#[FHIRBackboneElement(parentResource: 'ManufacturedItemDefinition', elementPath: 'ManufacturedItemDefinition.component', fhirVersion: 'R5')]
class FHIRManufacturedItemDefinitionComponent extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRCodeableConcept|null type Defining type of the component e.g. shell, layer, ink */
        #[NotBlank]
        public ?\FHIRCodeableConcept $type = null,
        /** @var array<FHIRCodeableConcept> function The function of this component within the item e.g. delivers active ingredient, masks taste */
        public array $function = [],
        /** @var array<FHIRQuantity> amount The measurable amount of total quantity of all substances in the component, expressable in different ways (e.g. by mass or volume) */
        public array $amount = [],
        /** @var array<FHIRManufacturedItemDefinitionComponentConstituent> constituent A reference to a constituent of the manufactured item as a whole, linked here so that its component location within the item can be indicated. This not where the item's ingredient are primarily stated (for which see Ingredient.for or ManufacturedItemDefinition.ingredient) */
        public array $constituent = [],
        /** @var array<FHIRManufacturedItemDefinitionProperty> property General characteristics of this component */
        public array $property = [],
        /** @var array<FHIRManufacturedItemDefinitionComponent> component A component that this component contains or is made from */
        public array $component = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
