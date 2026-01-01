<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

/**
 * @description The item that is being delivered or has been supplied.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'SupplyDelivery', elementPath: 'SupplyDelivery.suppliedItem', fhirVersion: 'R4')]
class FHIRSupplyDeliverySuppliedItem extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRQuantity quantity Amount dispensed */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRQuantity $quantity = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRReference itemX Medication, Substance, or Device supplied */
		public \Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRReference|null $itemX = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
