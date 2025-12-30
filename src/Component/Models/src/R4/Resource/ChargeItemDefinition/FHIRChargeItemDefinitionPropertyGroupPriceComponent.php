<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRInvoicePriceComponentTypeType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRMoney;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRDecimal;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description The price for a ChargeItem may be calculated as a base price with surcharges/deductions that apply in certain conditions. A ChargeItemDefinition resource that defines the prices, factors and conditions that apply to a billing code is currently under development. The priceComponent element can be used to offer transparency to the recipient of the Invoice of how the prices have been calculated.
 */
#[FHIRBackboneElement(
    parentResource: 'ChargeItemDefinition',
    elementPath: 'ChargeItemDefinition.propertyGroup.priceComponent',
    fhirVersion: 'R4',
)]
class FHIRChargeItemDefinitionPropertyGroupPriceComponent extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRInvoicePriceComponentTypeType|null type base | surcharge | deduction | discount | tax | informational */
        #[NotBlank]
        public ?FHIRInvoicePriceComponentTypeType $type = null,
        /** @var FHIRCodeableConcept|null code Code identifying the specific component */
        public ?FHIRCodeableConcept $code = null,
        /** @var FHIRDecimal|null factor Factor used for calculating this component */
        public ?FHIRDecimal $factor = null,
        /** @var FHIRMoney|null amount Monetary amount associated with this component */
        public ?FHIRMoney $amount = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
