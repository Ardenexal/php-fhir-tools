<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\Invoice;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\InvoicePriceComponentTypeType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Money;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description The price for a ChargeItem may be calculated as a base price with surcharges/deductions that apply in certain conditions. A ChargeItemDefinition resource that defines the prices, factors and conditions that apply to a billing code is currently under development. The priceComponent element can be used to offer transparency to the recipient of the Invoice as to how the prices have been calculated.
 */
#[FHIRBackboneElement(parentResource: 'Invoice', elementPath: 'Invoice.lineItem.priceComponent', fhirVersion: 'R4')]
class InvoiceLineItemPriceComponent extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var InvoicePriceComponentTypeType|null type base | surcharge | deduction | discount | tax | informational */
        #[NotBlank]
        public ?InvoicePriceComponentTypeType $type = null,
        /** @var CodeableConcept|null code Code identifying the specific component */
        public ?CodeableConcept $code = null,
        /** @var float|null factor Factor used for calculating this component */
        public ?float $factor = null,
        /** @var Money|null amount Monetary amount associated with this component */
        public ?Money $amount = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
