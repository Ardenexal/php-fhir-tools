<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Organization(s) responsible for the product.
 */
#[FHIRBackboneElement(parentResource: 'InventoryItem', elementPath: 'InventoryItem.responsibleOrganization', fhirVersion: 'R5')]
class FHIRInventoryItemResponsibleOrganization extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRCodeableConcept|null role The role of the organization e.g. manufacturer, distributor, or other */
        #[NotBlank]
        public ?\FHIRCodeableConcept $role = null,
        /** @var FHIRReference|null organization An organization that is associated with the item */
        #[NotBlank]
        public ?\FHIRReference $organization = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
