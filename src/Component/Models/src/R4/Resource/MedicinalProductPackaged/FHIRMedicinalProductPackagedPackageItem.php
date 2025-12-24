<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRIdentifier;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRProdCharacteristic;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRProductShelfLife;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRQuantity;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRReference;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description A packaging item, as a contained for medicine, possibly with other packaging items within.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'MedicinalProductPackaged', elementPath: 'MedicinalProductPackaged.packageItem', fhirVersion: 'R4')]
class FHIRMedicinalProductPackagedPackageItem extends FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var array<FHIRIdentifier> identifier Including possibly Data Carrier Identifier */
        public array $identifier = [],
        /** @var FHIRCodeableConcept|null type The physical type of the container of the medicine */
        #[NotBlank]
        public ?FHIRCodeableConcept $type = null,
        /** @var FHIRQuantity|null quantity The quantity of this package in the medicinal product, at the current level of packaging. The outermost is always 1 */
        #[NotBlank]
        public ?FHIRQuantity $quantity = null,
        /** @var array<FHIRCodeableConcept> material Material type of the package item */
        public array $material = [],
        /** @var array<FHIRCodeableConcept> alternateMaterial A possible alternate material for the packaging */
        public array $alternateMaterial = [],
        /** @var array<FHIRReference> device A device accompanying a medicinal product */
        public array $device = [],
        /** @var array<FHIRReference> manufacturedItem The manufactured item as contained in the packaged medicinal product */
        public array $manufacturedItem = [],
        /** @var array<FHIRMedicinalProductPackagedPackageItem> packageItem Allows containers within containers */
        public array $packageItem = [],
        /** @var FHIRProdCharacteristic|null physicalCharacteristics Dimensions, color etc. */
        public ?FHIRProdCharacteristic $physicalCharacteristics = null,
        /** @var array<FHIRCodeableConcept> otherCharacteristics Other codeable characteristics */
        public array $otherCharacteristics = [],
        /** @var array<FHIRProductShelfLife> shelfLifeStorage Shelf Life and storage information */
        public array $shelfLifeStorage = [],
        /** @var array<FHIRReference> manufacturer Manufacturer of this Package Item */
        public array $manufacturer = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
