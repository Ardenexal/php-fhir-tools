<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description The item(s) within the packaging.
 */
#[FHIRBackboneElement(
    parentResource: 'PackagedProductDefinition',
    elementPath: 'PackagedProductDefinition.packaging.containedItem',
    fhirVersion: 'R5',
)]
class FHIRPackagedProductDefinitionPackagingContainedItem extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRCodeableReference|null item The actual item(s) of medication, as manufactured, or a device, or other medically related item (food, biologicals, raw materials, medical fluids, gases etc.), as contained in the package */
        #[NotBlank]
        public ?\FHIRCodeableReference $item = null,
        /** @var FHIRQuantity|null amount The number of this type of item within this packaging or for continuous items such as liquids it is the quantity (for example 25ml). See also PackagedProductDefinition.containedItemQuantity (especially the long definition) */
        public ?\FHIRQuantity $amount = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
