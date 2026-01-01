<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableReference;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRQuantity;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description The item or items in this listing.
 */
#[FHIRBackboneElement(parentResource: 'InventoryReport', elementPath: 'InventoryReport.inventoryListing.item', fhirVersion: 'R5')]
class FHIRInventoryReportInventoryListingItem extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRCodeableConcept|null category The inventory category or classification of the items being reported */
        public ?FHIRCodeableConcept $category = null,
        /** @var FHIRQuantity|null quantity The quantity of the item or items being reported */
        #[NotBlank]
        public ?FHIRQuantity $quantity = null,
        /** @var FHIRCodeableReference|null item The code or reference to the item type */
        #[NotBlank]
        public ?FHIRCodeableReference $item = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
