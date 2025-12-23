<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-backbone-element InventoryItem.characteristic
 * @description The descriptive or identifying characteristics of the item.
 */
class FHIRInventoryItemCharacteristic extends \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept characteristicType The characteristic that is being defined */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept $characteristicType = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRInteger|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRDecimal|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBoolean|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRUrl|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRDateTime|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRQuantity|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRRange|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRRatio|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRAnnotation|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRAddress|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRDuration|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept valueX The value of the attribute */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRInteger|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRDecimal|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBoolean|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRUrl|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRDateTime|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRQuantity|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRRange|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRRatio|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRAnnotation|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRAddress|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRDuration|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept|null $valueX = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
