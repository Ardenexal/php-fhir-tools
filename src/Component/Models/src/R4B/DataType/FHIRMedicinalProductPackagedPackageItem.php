<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

/**
 * @fhir-backbone-element MedicinalProductPackaged.packageItem
 * @description A packaging item, as a contained for medicine, possibly with other packaging items within.
 */
class FHIRMedicinalProductPackagedPackageItem extends \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRIdentifier> identifier Including possibly Data Carrier Identifier */
		public array $identifier = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCodeableConcept type The physical type of the container of the medicine */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCodeableConcept $type = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRQuantity quantity The quantity of this package in the medicinal product, at the current level of packaging. The outermost is always 1 */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRQuantity $quantity = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCodeableConcept> material Material type of the package item */
		public array $material = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCodeableConcept> alternateMaterial A possible alternate material for the packaging */
		public array $alternateMaterial = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRReference> device A device accompanying a medicinal product */
		public array $device = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRReference> manufacturedItem The manufactured item as contained in the packaged medicinal product */
		public array $manufacturedItem = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRMedicinalProductPackagedPackageItem> packageItem Allows containers within containers */
		public array $packageItem = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRProdCharacteristic physicalCharacteristics Dimensions, color etc. */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRProdCharacteristic $physicalCharacteristics = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCodeableConcept> otherCharacteristics Other codeable characteristics */
		public array $otherCharacteristics = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRProductShelfLife> shelfLifeStorage Shelf Life and storage information */
		public array $shelfLifeStorage = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRReference> manufacturer Manufacturer of this Package Item */
		public array $manufacturer = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
