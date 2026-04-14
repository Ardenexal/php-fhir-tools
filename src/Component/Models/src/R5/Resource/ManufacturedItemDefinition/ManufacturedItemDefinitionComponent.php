<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource\ManufacturedItemDefinition;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Quantity;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Physical parts of the manufactured item, that it is intrisically made from. This is distinct from the ingredients that are part of its chemical makeup.
 */
#[FHIRBackboneElement(parentResource: 'ManufacturedItemDefinition', elementPath: 'ManufacturedItemDefinition.component', fhirVersion: 'R5')]
class ManufacturedItemDefinitionComponent extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        #[FhirProperty(fhirType: 'http://hl7.org/fhirpath/System.String', propertyKind: 'scalar', xmlSerializedName: '@id')]
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        #[FhirProperty(fhirType: 'Extension', propertyKind: 'extension', isArray: true)]
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        #[FhirProperty(fhirType: 'Extension', propertyKind: 'modifierExtension', isArray: true)]
        public array $modifierExtension = [],
        /** @var CodeableConcept|null type Defining type of the component e.g. shell, layer, ink */
        #[FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex', isRequired: true), NotBlank]
        public ?CodeableConcept $type = null,
        /** @var array<CodeableConcept> function The function of this component within the item e.g. delivers active ingredient, masks taste */
        #[FhirProperty(
            fhirType: 'CodeableConcept',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\DataType\CodeableConcept',
        )]
        public array $function = [],
        /** @var array<Quantity> amount The measurable amount of total quantity of all substances in the component, expressable in different ways (e.g. by mass or volume) */
        #[FhirProperty(
            fhirType: 'Quantity',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\DataType\Quantity',
        )]
        public array $amount = [],
        /** @var array<ManufacturedItemDefinitionComponentConstituent> constituent A reference to a constituent of the manufactured item as a whole, linked here so that its component location within the item can be indicated. This not where the item's ingredient are primarily stated (for which see Ingredient.for or ManufacturedItemDefinition.ingredient) */
        #[FhirProperty(
            fhirType: 'BackboneElement',
            propertyKind: 'backbone',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\Resource\ManufacturedItemDefinition\ManufacturedItemDefinitionComponentConstituent',
        )]
        public array $constituent = [],
        /** @var array<ManufacturedItemDefinitionProperty> property General characteristics of this component */
        #[FhirProperty(
            fhirType: 'unknown',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\Resource\ManufacturedItemDefinition\ManufacturedItemDefinitionProperty',
        )]
        public array $property = [],
        /** @var array<ManufacturedItemDefinitionComponent> component A component that this component contains or is made from */
        #[FhirProperty(
            fhirType: 'unknown',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\Resource\ManufacturedItemDefinition\ManufacturedItemDefinitionComponent',
        )]
        public array $component = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
