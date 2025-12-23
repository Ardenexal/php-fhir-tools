<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-backbone-element InventoryItem.instance
 * @description Instances or occurrences of the product.
 */
class FHIRInventoryItemInstance extends \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRIdentifier> identifier The identifier for the physical instance, typically a serial number */
		public array $identifier = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string lotNumber The lot or batch number of the item */
		public \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string|null $lotNumber = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRDateTime expiry The expiry date or date and time for the product */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRDateTime $expiry = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference subject The subject that the item is associated with */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference $subject = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference location The location that the item is associated with */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference $location = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
