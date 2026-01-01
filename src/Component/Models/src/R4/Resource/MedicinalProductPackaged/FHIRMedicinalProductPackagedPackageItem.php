<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

/**
 * @description A packaging item, as a contained for medicine, possibly with other packaging items within.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'MedicinalProductPackaged', elementPath: 'MedicinalProductPackaged.packageItem', fhirVersion: 'R4')]
class FHIRMedicinalProductPackagedPackageItem extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRIdentifier> identifier Including possibly Data Carrier Identifier */
		public array $identifier = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept type The physical type of the container of the medicine */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept $type = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRQuantity quantity The quantity of this package in the medicinal product, at the current level of packaging. The outermost is always 1 */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRQuantity $quantity = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept> material Material type of the package item */
		public array $material = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept> alternateMaterial A possible alternate material for the packaging */
		public array $alternateMaterial = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRReference> device A device accompanying a medicinal product */
		public array $device = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRReference> manufacturedItem The manufactured item as contained in the packaged medicinal product */
		public array $manufacturedItem = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRMedicinalProductPackagedPackageItem> packageItem Allows containers within containers */
		public array $packageItem = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRProdCharacteristic physicalCharacteristics Dimensions, color etc. */
		public ?FHIRProdCharacteristic $physicalCharacteristics = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept> otherCharacteristics Other codeable characteristics */
		public array $otherCharacteristics = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRProductShelfLife> shelfLifeStorage Shelf Life and storage information */
		public array $shelfLifeStorage = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRReference> manufacturer Manufacturer of this Package Item */
		public array $manufacturer = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
