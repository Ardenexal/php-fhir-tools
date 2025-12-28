<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description The first-tier service adjudications for payor added product or service lines.
 */
#[FHIRBackboneElement(parentResource: 'ExplanationOfBenefit', elementPath: 'ExplanationOfBenefit.addItem', fhirVersion: 'R4B')]
class FHIRExplanationOfBenefitAddItem extends \Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRBackboneElement
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
        /** @var array<FHIRReference> provider Authorized providers */
        public array $provider = [],
        /** @var FHIRCodeableConcept|null productOrService Billing, service, product, or drug code */
        #[NotBlank]
        public ?\FHIRCodeableConcept $productOrService = null,
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
        /** @var FHIRMoney|null net Total item cost */
        public ?\FHIRMoney $net = null,
        /** @var FHIRCodeableConcept|null bodySite Anatomical location */
        public ?\FHIRCodeableConcept $bodySite = null,
        /** @var array<FHIRCodeableConcept> subSite Anatomical sub-location */
        public array $subSite = [],
        /** @var array<FHIRPositiveInt> noteNumber Applicable note numbers */
        public array $noteNumber = [],
        /** @var array<FHIRExplanationOfBenefitItemAdjudication> adjudication Added items adjudication */
        public array $adjudication = [],
        /** @var array<FHIRExplanationOfBenefitAddItemDetail> detail Insurer added line items */
        public array $detail = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
