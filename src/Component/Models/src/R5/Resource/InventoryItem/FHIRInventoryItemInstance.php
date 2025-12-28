<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;

/**
 * @description Instances or occurrences of the product.
 */
#[FHIRBackboneElement(parentResource: 'InventoryItem', elementPath: 'InventoryItem.instance', fhirVersion: 'R5')]
class FHIRInventoryItemInstance extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var array<FHIRIdentifier> identifier The identifier for the physical instance, typically a serial number */
        public array $identifier = [],
        /** @var FHIRString|string|null lotNumber The lot or batch number of the item */
        public \FHIRString|string|null $lotNumber = null,
        /** @var FHIRDateTime|null expiry The expiry date or date and time for the product */
        public ?\FHIRDateTime $expiry = null,
        /** @var FHIRReference|null subject The subject that the item is associated with */
        public ?\FHIRReference $subject = null,
        /** @var FHIRReference|null location The location that the item is associated with */
        public ?\FHIRReference $location = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
