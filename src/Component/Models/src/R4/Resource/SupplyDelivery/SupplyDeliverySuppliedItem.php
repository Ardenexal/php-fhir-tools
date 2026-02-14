<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\SupplyDelivery;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Quantity;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference;

/**
 * @description The item that is being delivered or has been supplied.
 */
#[FHIRBackboneElement(parentResource: 'SupplyDelivery', elementPath: 'SupplyDelivery.suppliedItem', fhirVersion: 'R4')]
class SupplyDeliverySuppliedItem extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var Quantity|null quantity Amount dispensed */
        public ?Quantity $quantity = null,
        /** @var CodeableConcept|Reference|null itemX Medication, Substance, or Device supplied */
        public CodeableConcept|Reference|null $itemX = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
