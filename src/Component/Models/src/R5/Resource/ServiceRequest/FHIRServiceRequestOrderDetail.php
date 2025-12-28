<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;

/**
 * @description Additional details and instructions about the how the services are to be delivered.   For example, and order for a urinary catheter may have an order detail for an external or indwelling catheter, or an order for a bandage may require additional instructions specifying how the bandage should be applied.
 */
#[FHIRBackboneElement(parentResource: 'ServiceRequest', elementPath: 'ServiceRequest.orderDetail', fhirVersion: 'R5')]
class FHIRServiceRequestOrderDetail extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRCodeableReference|null parameterFocus The context of the order details by reference */
        public ?\FHIRCodeableReference $parameterFocus = null,
        /** @var array<FHIRServiceRequestOrderDetailParameter> parameter The parameter details for the service being requested */
        public array $parameter = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
