<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

/**
 * @description The descriptive or identifying characteristics of the item.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'InventoryItem', elementPath: 'InventoryItem.characteristic', fhirVersion: 'R5')]
class FHIRInventoryItemCharacteristic extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept characteristicType The characteristic that is being defined */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept $characteristicType = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRInteger|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDecimal|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRBoolean|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRUrl|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDateTime|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRQuantity|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRRange|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRRatio|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRAnnotation|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRAddress|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRDuration|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept valueX The value of the attribute */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public \Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRInteger|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDecimal|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRBoolean|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRUrl|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDateTime|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRQuantity|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRRange|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRRatio|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRAnnotation|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRAddress|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRDuration|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept|null $valueX = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
