<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\MedicinalProductPackaged;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
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
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var array<Identifier> identifier Including possibly Data Carrier Identifier */
        public array $identifier = [],
        /** @var CodeableConcept|null type The physical type of the container of the medicine */
        #[NotBlank]
        public ?CodeableConcept $type = null,
        /** @var Quantity|null quantity The quantity of this package in the medicinal product, at the current level of packaging. The outermost is always 1 */
        #[NotBlank]
        public ?Quantity $quantity = null,
        /** @var array<CodeableConcept> material Material type of the package item */
        public array $material = [],
        /** @var array<CodeableConcept> alternateMaterial A possible alternate material for the packaging */
        public array $alternateMaterial = [],
        /** @var array<Reference> device A device accompanying a medicinal product */
        public array $device = [],
        /** @var array<Reference> manufacturedItem The manufactured item as contained in the packaged medicinal product */
        public array $manufacturedItem = [],
        /** @var array<MedicinalProductPackagedPackageItem> packageItem Allows containers within containers */
        public array $packageItem = [],
        /** @var ProdCharacteristic|null physicalCharacteristics Dimensions, color etc. */
        public ?ProdCharacteristic $physicalCharacteristics = null,
        /** @var array<CodeableConcept> otherCharacteristics Other codeable characteristics */
        public array $otherCharacteristics = [],
        /** @var array<ProductShelfLife> shelfLifeStorage Shelf Life and storage information */
        public array $shelfLifeStorage = [],
        /** @var array<Reference> manufacturer Manufacturer of this Package Item */
        public array $manufacturer = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
