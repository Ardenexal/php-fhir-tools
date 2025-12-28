<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description The third-tier service adjudications for payor added services.
 */
#[FHIRBackboneElement(parentResource: 'ExplanationOfBenefit', elementPath: 'ExplanationOfBenefit.addItem.detail.subDetail', fhirVersion: 'R4')]
class FHIRExplanationOfBenefitAddItemDetailSubDetail extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRCodeableConcept|null productOrService Billing, service, product, or drug code */
        #[NotBlank]
        public ?\FHIRCodeableConcept $productOrService = null,
        /** @var array<FHIRCodeableConcept> modifier Service/Product billing modifiers */
        public array $modifier = [],
        /** @var FHIRQuantity|null quantity Count of products or services */
        public ?\FHIRQuantity $quantity = null,
        /** @var FHIRMoney|null unitPrice Fee, charge or cost per item */
        public ?\FHIRMoney $unitPrice = null,
        /** @var FHIRDecimal|null factor Price scaling factor */
        public ?\FHIRDecimal $factor = null,
        /** @var FHIRMoney|null net Total item cost */
        public ?\FHIRMoney $net = null,
        /** @var array<FHIRPositiveInt> noteNumber Applicable note numbers */
        public array $noteNumber = [],
        /** @var array<FHIRExplanationOfBenefitItemAdjudication> adjudication Added items adjudication */
        public array $adjudication = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
