<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\Invoice;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\PositiveIntPrimitive;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Each line item represents one charge for goods and services rendered. Details such as date, code and amount are found in the referenced ChargeItem resource.
 */
#[FHIRBackboneElement(parentResource: 'Invoice', elementPath: 'Invoice.lineItem', fhirVersion: 'R4')]
class InvoiceLineItem extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var PositiveIntPrimitive|null sequence Sequence number of line item */
        public ?PositiveIntPrimitive $sequence = null,
        /** @var Reference|CodeableConcept|null chargeItemX Reference to ChargeItem containing details of this line item or an inline billing code */
        #[NotBlank]
        public Reference|CodeableConcept|null $chargeItemX = null,
        /** @var array<InvoiceLineItemPriceComponent> priceComponent Components of total line item price */
        public array $priceComponent = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
