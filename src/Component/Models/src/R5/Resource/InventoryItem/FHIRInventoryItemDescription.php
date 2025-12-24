<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString;

/**
 * @description The descriptive characteristics of the inventory item.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'InventoryItem', elementPath: 'InventoryItem.description', fhirVersion: 'R5')]
class FHIRInventoryItemDescription extends FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRCommonLanguagesType|null language The language that is used in the item description */
        public ?FHIRCommonLanguagesType $language = null,
        /** @var FHIRString|string|null description Textual description of the item */
        public FHIRString|string|null $description = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
