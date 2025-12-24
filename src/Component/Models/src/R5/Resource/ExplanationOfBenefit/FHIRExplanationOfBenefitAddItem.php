<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRAddress;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRIdentifier;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRMoney;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRPeriod;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRQuantity;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDate;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDecimal;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRPositiveInt;

/**
 * @description The first-tier service adjudications for payor added product or service lines.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'ExplanationOfBenefit', elementPath: 'ExplanationOfBenefit.addItem', fhirVersion: 'R5')]
class FHIRExplanationOfBenefitAddItem extends FHIRBackboneElement
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
        /** @var array<FHIRPositiveInt> subDetailSequence Subdetail sequence number */
        public array $subDetailSequence = [],
        /** @var array<FHIRIdentifier> traceNumber Number for tracking */
        public array $traceNumber = [],
        /** @var array<FHIRReference> provider Authorized providers */
        public array $provider = [],
        /** @var FHIRCodeableConcept|null revenue Revenue or cost center code */
        public ?FHIRCodeableConcept $revenue = null,
        /** @var FHIRCodeableConcept|null productOrService Billing, service, product, or drug code */
        public ?FHIRCodeableConcept $productOrService = null,
        /** @var FHIRCodeableConcept|null productOrServiceEnd End of a range of codes */
        public ?FHIRCodeableConcept $productOrServiceEnd = null,
        /** @var array<FHIRReference> request Request or Referral for Service */
        public array $request = [],
        /** @var array<FHIRCodeableConcept> modifier Service/Product billing modifiers */
        public array $modifier = [],
        /** @var array<FHIRCodeableConcept> programCode Program the product or service is provided under */
        public array $programCode = [],
        /** @var FHIRDate|FHIRPeriod|null servicedX Date or dates of service or product delivery */
        public FHIRDate|FHIRPeriod|null $servicedX = null,
        /** @var FHIRCodeableConcept|FHIRAddress|FHIRReference|null locationX Place of service or where product was supplied */
        public FHIRCodeableConcept|FHIRAddress|FHIRReference|null $locationX = null,
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
        /** @var array<FHIRExplanationOfBenefitAddItemBodySite> bodySite Anatomical location */
        public array $bodySite = [],
        /** @var array<FHIRPositiveInt> noteNumber Applicable note numbers */
        public array $noteNumber = [],
        /** @var FHIRExplanationOfBenefitItemReviewOutcome|null reviewOutcome Additem level adjudication results */
        public ?FHIRExplanationOfBenefitItemReviewOutcome $reviewOutcome = null,
        /** @var array<FHIRExplanationOfBenefitItemAdjudication> adjudication Added items adjudication */
        public array $adjudication = [],
        /** @var array<FHIRExplanationOfBenefitAddItemDetail> detail Insurer added line items */
        public array $detail = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
