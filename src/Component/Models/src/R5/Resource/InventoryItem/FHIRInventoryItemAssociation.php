<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Association with other items or products.
 */
#[FHIRBackboneElement(parentResource: 'InventoryItem', elementPath: 'InventoryItem.association', fhirVersion: 'R5')]
class FHIRInventoryItemAssociation extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRCodeableConcept|null associationType The type of association between the device and the other item */
        #[NotBlank]
        public ?\FHIRCodeableConcept $associationType = null,
        /** @var FHIRReference|null relatedItem The related item or product */
        #[NotBlank]
        public ?\FHIRReference $relatedItem = null,
        /** @var FHIRRatio|null quantity The quantity of the product in this product */
        #[NotBlank]
        public ?\FHIRRatio $quantity = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
