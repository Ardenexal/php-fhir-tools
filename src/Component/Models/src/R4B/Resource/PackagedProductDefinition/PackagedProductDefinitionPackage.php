<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource\PackagedProductDefinition;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Identifier;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Reference;

/**
 * @description A packaging item, as a container for medically related items, possibly with other packaging items within, or a packaging component, such as bottle cap (which is not a device or a medication manufactured item).
 */
#[FHIRBackboneElement(parentResource: 'PackagedProductDefinition', elementPath: 'PackagedProductDefinition.package', fhirVersion: 'R4B')]
class PackagedProductDefinitionPackage extends BackboneElement
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
        /** @var array<Identifier> identifier An identifier that is specific to this particular part of the packaging. Including possibly a Data Carrier Identifier */
        #[FhirProperty(
            fhirType: 'Identifier',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R4B\DataType\Identifier',
        )]
        public array $identifier = [],
        /** @var CodeableConcept|null type The physical type of the container of the items */
        #[FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex')]
        public ?CodeableConcept $type = null,
        /** @var int|null quantity The quantity of this level of packaging in the package that contains it (with the outermost level being 1) */
        #[FhirProperty(fhirType: 'integer', propertyKind: 'scalar')]
        public ?int $quantity = null,
        /** @var array<CodeableConcept> material Material type of the package item */
        #[FhirProperty(
            fhirType: 'CodeableConcept',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R4B\DataType\CodeableConcept',
        )]
        public array $material = [],
        /** @var array<CodeableConcept> alternateMaterial A possible alternate material for this part of the packaging, that is allowed to be used instead of the usual material */
        #[FhirProperty(
            fhirType: 'CodeableConcept',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R4B\DataType\CodeableConcept',
        )]
        public array $alternateMaterial = [],
        /** @var array<PackagedProductDefinitionPackageShelfLifeStorage> shelfLifeStorage Shelf Life and storage information */
        #[FhirProperty(
            fhirType: 'BackboneElement',
            propertyKind: 'backbone',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R4B\Resource\PackagedProductDefinition\PackagedProductDefinitionPackageShelfLifeStorage',
        )]
        public array $shelfLifeStorage = [],
        /** @var array<Reference> manufacturer Manufacturer of this package Item (multiple means these are all possible manufacturers) */
        #[FhirProperty(
            fhirType: 'Reference',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R4B\DataType\Reference',
        )]
        public array $manufacturer = [],
        /** @var array<PackagedProductDefinitionPackageProperty> property General characteristics of this item */
        #[FhirProperty(
            fhirType: 'BackboneElement',
            propertyKind: 'backbone',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R4B\Resource\PackagedProductDefinition\PackagedProductDefinitionPackageProperty',
        )]
        public array $property = [],
        /** @var array<PackagedProductDefinitionPackageContainedItem> containedItem The item(s) within the packaging */
        #[FhirProperty(
            fhirType: 'BackboneElement',
            propertyKind: 'backbone',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R4B\Resource\PackagedProductDefinition\PackagedProductDefinitionPackageContainedItem',
        )]
        public array $containedItem = [],
        /** @var array<PackagedProductDefinitionPackage> package Allows containers (and parts of containers) within containers, still a single packaged product */
        #[FhirProperty(
            fhirType: 'unknown',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R4B\Resource\PackagedProductDefinition\PackagedProductDefinitionPackage',
        )]
        public array $package = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
