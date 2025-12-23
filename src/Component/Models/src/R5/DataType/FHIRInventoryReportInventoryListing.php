<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-backbone-element InventoryReport.inventoryListing
 * @description An inventory listing section (grouped by any of the attributes).
 */
class FHIRInventoryReportInventoryListing extends \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference location Location of the inventory items */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference $location = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept itemStatus The status of the items that are being reported */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept $itemStatus = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRDateTime countingDateTime The date and time when the items were counted */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRDateTime $countingDateTime = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRInventoryReportInventoryListingItem> item The item or items in this listing */
		public array $item = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
