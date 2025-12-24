<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDateTime;

/**
 * @description An inventory listing section (grouped by any of the attributes).
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'InventoryReport', elementPath: 'InventoryReport.inventoryListing', fhirVersion: 'R5')]
class FHIRInventoryReportInventoryListing extends FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRReference|null location Location of the inventory items */
        public ?FHIRReference $location = null,
        /** @var FHIRCodeableConcept|null itemStatus The status of the items that are being reported */
        public ?FHIRCodeableConcept $itemStatus = null,
        /** @var FHIRDateTime|null countingDateTime The date and time when the items were counted */
        public ?FHIRDateTime $countingDateTime = null,
        /** @var array<FHIRInventoryReportInventoryListingItem> item The item or items in this listing */
        public array $item = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
