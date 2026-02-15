<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\ExplanationOfBenefit;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Money;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Quantity;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\PositiveIntPrimitive;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Second-tier of goods and services.
 */
#[FHIRBackboneElement(parentResource: 'ExplanationOfBenefit', elementPath: 'ExplanationOfBenefit.item.detail', fhirVersion: 'R4')]
class ExplanationOfBenefitItemDetail extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var PositiveIntPrimitive|null sequence Product or service provided */
        #[NotBlank]
        public ?PositiveIntPrimitive $sequence = null,
        /** @var CodeableConcept|null revenue Revenue or cost center code */
        public ?CodeableConcept $revenue = null,
        /** @var CodeableConcept|null category Benefit classification */
        public ?CodeableConcept $category = null,
        /** @var CodeableConcept|null productOrService Billing, service, product, or drug code */
        #[NotBlank]
        public ?CodeableConcept $productOrService = null,
        /** @var array<CodeableConcept> modifier Service/Product billing modifiers */
        public array $modifier = [],
        /** @var array<CodeableConcept> programCode Program the product or service is provided under */
        public array $programCode = [],
        /** @var Quantity|null quantity Count of products or services */
        public ?Quantity $quantity = null,
        /** @var Money|null unitPrice Fee, charge or cost per item */
        public ?Money $unitPrice = null,
        /** @var float|null factor Price scaling factor */
        public ?float $factor = null,
        /** @var Money|null net Total item cost */
        public ?Money $net = null,
        /** @var array<Reference> udi Unique device identifier */
        public array $udi = [],
        /** @var array<PositiveIntPrimitive> noteNumber Applicable note numbers */
        public array $noteNumber = [],
        /** @var array<ExplanationOfBenefitItemAdjudication> adjudication Detail level adjudication details */
        public array $adjudication = [],
        /** @var array<ExplanationOfBenefitItemDetailSubDetail> subDetail Additional items */
        public array $subDetail = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
