<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\MedicinalProductPackaged;

/**
 * @description A packaging item, as a contained for medicine, possibly with other packaging items within.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'MedicinalProductPackaged', elementPath: 'MedicinalProductPackaged.packageItem', fhirVersion: 'R4')]
class MedicinalProductPackagedPackageItem extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier> identifier Including possibly Data Carrier Identifier */
		public array $identifier = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept type The physical type of the container of the medicine */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $type = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Quantity quantity The quantity of this package in the medicinal product, at the current level of packaging. The outermost is always 1 */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Quantity $quantity = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept> material Material type of the package item */
		public array $material = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept> alternateMaterial A possible alternate material for the packaging */
		public array $alternateMaterial = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference> device A device accompanying a medicinal product */
		public array $device = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference> manufacturedItem The manufactured item as contained in the packaged medicinal product */
		public array $manufacturedItem = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\MedicinalProductPackaged\MedicinalProductPackagedPackageItem> packageItem Allows containers within containers */
		public array $packageItem = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\ProdCharacteristic physicalCharacteristics Dimensions, color etc. */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\ProdCharacteristic $physicalCharacteristics = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept> otherCharacteristics Other codeable characteristics */
		public array $otherCharacteristics = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\ProductShelfLife> shelfLifeStorage Shelf Life and storage information */
		public array $shelfLifeStorage = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference> manufacturer Manufacturer of this Package Item */
		public array $manufacturer = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
