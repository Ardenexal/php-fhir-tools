<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\ExplanationOfBenefit;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Address;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Money;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Period;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Quantity;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\DatePrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\PositiveIntPrimitive;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description The first-tier service adjudications for payor added product or service lines.
 */
#[FHIRBackboneElement(parentResource: 'ExplanationOfBenefit', elementPath: 'ExplanationOfBenefit.addItem', fhirVersion: 'R4')]
class ExplanationOfBenefitAddItem extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var array<PositiveIntPrimitive> itemSequence Item sequence number */
        public array $itemSequence = [],
        /** @var array<PositiveIntPrimitive> detailSequence Detail sequence number */
        public array $detailSequence = [],
        /** @var array<PositiveIntPrimitive> subDetailSequence Subdetail sequence number */
        public array $subDetailSequence = [],
        /** @var array<Reference> provider Authorized providers */
        public array $provider = [],
        /** @var CodeableConcept|null productOrService Billing, service, product, or drug code */
        #[NotBlank]
        public ?CodeableConcept $productOrService = null,
        /** @var array<CodeableConcept> modifier Service/Product billing modifiers */
        public array $modifier = [],
        /** @var array<CodeableConcept> programCode Program the product or service is provided under */
        public array $programCode = [],
        /** @var DatePrimitive|Period|null servicedX Date or dates of service or product delivery */
        public DatePrimitive|Period|null $servicedX = null,
        /** @var CodeableConcept|Address|Reference|null locationX Place of service or where product was supplied */
        public CodeableConcept|Address|Reference|null $locationX = null,
        /** @var Quantity|null quantity Count of products or services */
        public ?Quantity $quantity = null,
        /** @var Money|null unitPrice Fee, charge or cost per item */
        public ?Money $unitPrice = null,
        /** @var float|null factor Price scaling factor */
        public ?float $factor = null,
        /** @var Money|null net Total item cost */
        public ?Money $net = null,
        /** @var CodeableConcept|null bodySite Anatomical location */
        public ?CodeableConcept $bodySite = null,
        /** @var array<CodeableConcept> subSite Anatomical sub-location */
        public array $subSite = [],
        /** @var array<PositiveIntPrimitive> noteNumber Applicable note numbers */
        public array $noteNumber = [],
        /** @var array<ExplanationOfBenefitItemAdjudication> adjudication Added items adjudication */
        public array $adjudication = [],
        /** @var array<ExplanationOfBenefitAddItemDetail> detail Insurer added line items */
        public array $detail = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
