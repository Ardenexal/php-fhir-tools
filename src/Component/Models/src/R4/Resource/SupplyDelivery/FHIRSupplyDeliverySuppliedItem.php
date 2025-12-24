<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRQuantity;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRReference;

/**
 * @description The item that is being delivered or has been supplied.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'SupplyDelivery', elementPath: 'SupplyDelivery.suppliedItem', fhirVersion: 'R4')]
class FHIRSupplyDeliverySuppliedItem extends FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRQuantity|null quantity Amount dispensed */
        public ?FHIRQuantity $quantity = null,
        /** @var FHIRCodeableConcept|FHIRReference|null itemX Medication, Substance, or Device supplied */
        public FHIRCodeableConcept|FHIRReference|null $itemX = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
