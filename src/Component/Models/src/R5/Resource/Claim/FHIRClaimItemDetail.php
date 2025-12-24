<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRIdentifier;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRMoney;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRQuantity;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDecimal;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRPositiveInt;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description A claim detail line. Either a simple (a product or service) or a 'group' of sub-details which are simple items.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'Claim', elementPath: 'Claim.item.detail', fhirVersion: 'R5')]
class FHIRClaimItemDetail extends FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRPositiveInt|null sequence Item instance identifier */
        #[NotBlank]
        public ?FHIRPositiveInt $sequence = null,
        /** @var array<FHIRIdentifier> traceNumber Number for tracking */
        public array $traceNumber = [],
        /** @var FHIRCodeableConcept|null revenue Revenue or cost center code */
        public ?FHIRCodeableConcept $revenue = null,
        /** @var FHIRCodeableConcept|null category Benefit classification */
        public ?FHIRCodeableConcept $category = null,
        /** @var FHIRCodeableConcept|null productOrService Billing, service, product, or drug code */
        public ?FHIRCodeableConcept $productOrService = null,
        /** @var FHIRCodeableConcept|null productOrServiceEnd End of a range of codes */
        public ?FHIRCodeableConcept $productOrServiceEnd = null,
        /** @var array<FHIRCodeableConcept> modifier Service/Product billing modifiers */
        public array $modifier = [],
        /** @var array<FHIRCodeableConcept> programCode Program the product or service is provided under */
        public array $programCode = [],
        /** @var FHIRMoney|null patientPaid Paid by the patient */
        public ?FHIRMoney $patientPaid = null,
        /** @var FHIRQuantity|null quantity Count of products or services */
        public ?FHIRQuantity $quantity = null,
        /** @var FHIRMoney|null unitPrice Fee, charge or cost per item */
        public ?FHIRMoney $unitPrice = null,
        /** @var FHIRDecimal|null factor Price scaling factor */
        public ?FHIRDecimal $factor = null,
        /** @var FHIRMoney|null tax Total tax */
        public ?FHIRMoney $tax = null,
        /** @var FHIRMoney|null net Total item cost */
        public ?FHIRMoney $net = null,
        /** @var array<FHIRReference> udi Unique device identifier */
        public array $udi = [],
        /** @var array<FHIRClaimItemDetailSubDetail> subDetail Product or service provided */
        public array $subDetail = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
