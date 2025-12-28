<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCoding;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description The item name(s) - the brand name, or common name, functional name, generic name.
 */
#[FHIRBackboneElement(parentResource: 'InventoryItem', elementPath: 'InventoryItem.name', fhirVersion: 'R5')]
class FHIRInventoryItemName extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRCoding|null nameType The type of name e.g. 'brand-name', 'functional-name', 'common-name' */
        #[NotBlank]
        public ?FHIRCoding $nameType = null,
        /** @var FHIRCommonLanguagesType|null language The language used to express the item name */
        #[NotBlank]
        public ?FHIRCommonLanguagesType $language = null,
        /** @var FHIRString|string|null name The name or designation of the item */
        #[NotBlank]
        public FHIRString|string|null $name = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
