<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\MedicinalProductPackaged;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\ProdCharacteristic;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\ProductShelfLife;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Quantity;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description A packaging item, as a contained for medicine, possibly with other packaging items within.
 */
#[FHIRBackboneElement(parentResource: 'MedicinalProductPackaged', elementPath: 'MedicinalProductPackaged.packageItem', fhirVersion: 'R4')]
class MedicinalProductPackagedPackageItem extends BackboneElement
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
        /** @var array<Identifier> identifier Including possibly Data Carrier Identifier */
        #[FhirProperty(
            fhirType: 'Identifier',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier',
        )]
        public array $identifier = [],
        /** @var CodeableConcept|null type The physical type of the container of the medicine */
        #[FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex', isRequired: true), NotBlank]
        public ?CodeableConcept $type = null,
        /** @var Quantity|null quantity The quantity of this package in the medicinal product, at the current level of packaging. The outermost is always 1 */
        #[FhirProperty(fhirType: 'Quantity', propertyKind: 'complex', isRequired: true), NotBlank]
        public ?Quantity $quantity = null,
        /** @var array<CodeableConcept> material Material type of the package item */
        #[FhirProperty(
            fhirType: 'CodeableConcept',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept',
        )]
        public array $material = [],
        /** @var array<CodeableConcept> alternateMaterial A possible alternate material for the packaging */
        #[FhirProperty(
            fhirType: 'CodeableConcept',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept',
        )]
        public array $alternateMaterial = [],
        /** @var array<Reference> device A device accompanying a medicinal product */
        #[FhirProperty(
            fhirType: 'Reference',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference',
        )]
        public array $device = [],
        /** @var array<Reference> manufacturedItem The manufactured item as contained in the packaged medicinal product */
        #[FhirProperty(
            fhirType: 'Reference',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference',
        )]
        public array $manufacturedItem = [],
        /** @var array<MedicinalProductPackagedPackageItem> packageItem Allows containers within containers */
        #[FhirProperty(
            fhirType: 'unknown',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R4\Resource\MedicinalProductPackaged\MedicinalProductPackagedPackageItem',
        )]
        public array $packageItem = [],
        /** @var ProdCharacteristic|null physicalCharacteristics Dimensions, color etc. */
        #[FhirProperty(fhirType: 'ProdCharacteristic', propertyKind: 'complex')]
        public ?ProdCharacteristic $physicalCharacteristics = null,
        /** @var array<CodeableConcept> otherCharacteristics Other codeable characteristics */
        #[FhirProperty(
            fhirType: 'CodeableConcept',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept',
        )]
        public array $otherCharacteristics = [],
        /** @var array<ProductShelfLife> shelfLifeStorage Shelf Life and storage information */
        #[FhirProperty(
            fhirType: 'ProductShelfLife',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R4\DataType\ProductShelfLife',
        )]
        public array $shelfLifeStorage = [],
        /** @var array<Reference> manufacturer Manufacturer of this Package Item */
        #[FhirProperty(
            fhirType: 'Reference',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference',
        )]
        public array $manufacturer = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
