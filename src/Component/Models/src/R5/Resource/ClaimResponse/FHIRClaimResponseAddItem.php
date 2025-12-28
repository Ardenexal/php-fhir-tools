<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;

/**
 * @description The first-tier service adjudications for payor added product or service lines.
 */
#[FHIRBackboneElement(parentResource: 'ClaimResponse', elementPath: 'ClaimResponse.addItem', fhirVersion: 'R5')]
class FHIRClaimResponseAddItem extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var array<FHIRPositiveInt> itemSequence Item sequence number */
        public array $itemSequence = [],
        /** @var array<FHIRPositiveInt> detailSequence Detail sequence number */
        public array $detailSequence = [],
        /** @var array<FHIRPositiveInt> subdetailSequence Subdetail sequence number */
        public array $subdetailSequence = [],
        /** @var array<FHIRIdentifier> traceNumber Number for tracking */
        public array $traceNumber = [],
        /** @var array<FHIRReference> provider Authorized providers */
        public array $provider = [],
        /** @var FHIRCodeableConcept|null revenue Revenue or cost center code */
        public ?\FHIRCodeableConcept $revenue = null,
        /** @var FHIRCodeableConcept|null productOrService Billing, service, product, or drug code */
        public ?\FHIRCodeableConcept $productOrService = null,
        /** @var FHIRCodeableConcept|null productOrServiceEnd End of a range of codes */
        public ?\FHIRCodeableConcept $productOrServiceEnd = null,
        /** @var array<FHIRReference> request Request or Referral for Service */
        public array $request = [],
        /** @var array<FHIRCodeableConcept> modifier Service/Product billing modifiers */
        public array $modifier = [],
        /** @var array<FHIRCodeableConcept> programCode Program the product or service is provided under */
        public array $programCode = [],
        /** @var FHIRDate|FHIRPeriod|null servicedX Date or dates of service or product delivery */
        public \FHIRDate|\FHIRPeriod|null $servicedX = null,
        /** @var FHIRCodeableConcept|FHIRAddress|FHIRReference|null locationX Place of service or where product was supplied */
        public \FHIRCodeableConcept|\FHIRAddress|\FHIRReference|null $locationX = null,
        /** @var FHIRQuantity|null quantity Count of products or services */
        public ?\FHIRQuantity $quantity = null,
        /** @var FHIRMoney|null unitPrice Fee, charge or cost per item */
        public ?\FHIRMoney $unitPrice = null,
        /** @var FHIRDecimal|null factor Price scaling factor */
        public ?\FHIRDecimal $factor = null,
        /** @var FHIRMoney|null tax Total tax */
        public ?\FHIRMoney $tax = null,
        /** @var FHIRMoney|null net Total item cost */
        public ?\FHIRMoney $net = null,
        /** @var array<FHIRClaimResponseAddItemBodySite> bodySite Anatomical location */
        public array $bodySite = [],
        /** @var array<FHIRPositiveInt> noteNumber Applicable note numbers */
        public array $noteNumber = [],
        /** @var FHIRClaimResponseItemReviewOutcome|null reviewOutcome Added items adjudication results */
        public ?\FHIRClaimResponseItemReviewOutcome $reviewOutcome = null,
        /** @var array<FHIRClaimResponseItemAdjudication> adjudication Added items adjudication */
        public array $adjudication = [],
        /** @var array<FHIRClaimResponseAddItemDetail> detail Insurer added line details */
        public array $detail = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
