<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Third-tier of goods and services.
 */
#[FHIRBackboneElement(parentResource: 'ExplanationOfBenefit', elementPath: 'ExplanationOfBenefit.item.detail.subDetail', fhirVersion: 'R4B')]
class FHIRExplanationOfBenefitItemDetailSubDetail extends \Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRPositiveInt|null sequence Product or service provided */
        #[NotBlank]
        public ?\FHIRPositiveInt $sequence = null,
        /** @var FHIRCodeableConcept|null revenue Revenue or cost center code */
        public ?\FHIRCodeableConcept $revenue = null,
        /** @var FHIRCodeableConcept|null category Benefit classification */
        public ?\FHIRCodeableConcept $category = null,
        /** @var FHIRCodeableConcept|null productOrService Billing, service, product, or drug code */
        #[NotBlank]
        public ?\FHIRCodeableConcept $productOrService = null,
        /** @var array<FHIRCodeableConcept> modifier Service/Product billing modifiers */
        public array $modifier = [],
        /** @var array<FHIRCodeableConcept> programCode Program the product or service is provided under */
        public array $programCode = [],
        /** @var FHIRQuantity|null quantity Count of products or services */
        public ?\FHIRQuantity $quantity = null,
        /** @var FHIRMoney|null unitPrice Fee, charge or cost per item */
        public ?\FHIRMoney $unitPrice = null,
        /** @var FHIRDecimal|null factor Price scaling factor */
        public ?\FHIRDecimal $factor = null,
        /** @var FHIRMoney|null net Total item cost */
        public ?\FHIRMoney $net = null,
        /** @var array<FHIRReference> udi Unique device identifier */
        public array $udi = [],
        /** @var array<FHIRPositiveInt> noteNumber Applicable note numbers */
        public array $noteNumber = [],
        /** @var array<FHIRExplanationOfBenefitItemAdjudication> adjudication Subdetail level adjudication details */
        public array $adjudication = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
