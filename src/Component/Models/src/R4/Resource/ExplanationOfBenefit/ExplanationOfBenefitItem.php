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
 * @description A claim line. Either a simple (a product or service) or a 'group' of details which can also be a simple items or groups of sub-details.
 */
#[FHIRBackboneElement(parentResource: 'ExplanationOfBenefit', elementPath: 'ExplanationOfBenefit.item', fhirVersion: 'R4')]
class ExplanationOfBenefitItem extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var PositiveIntPrimitive|null sequence Item instance identifier */
        #[NotBlank]
        public ?PositiveIntPrimitive $sequence = null,
        /** @var array<PositiveIntPrimitive> careTeamSequence Applicable care team members */
        public array $careTeamSequence = [],
        /** @var array<PositiveIntPrimitive> diagnosisSequence Applicable diagnoses */
        public array $diagnosisSequence = [],
        /** @var array<PositiveIntPrimitive> procedureSequence Applicable procedures */
        public array $procedureSequence = [],
        /** @var array<PositiveIntPrimitive> informationSequence Applicable exception and supporting information */
        public array $informationSequence = [],
        /** @var CodeableConcept|null revenue Revenue or cost center code */
        public ?CodeableConcept $revenue = null,
        /** @var CodeableConcept|null category Benefit classification */
        public ?CodeableConcept $category = null,
        /** @var CodeableConcept|null productOrService Billing, service, product, or drug code */
        #[NotBlank]
        public ?CodeableConcept $productOrService = null,
        /** @var array<CodeableConcept> modifier Product or service billing modifiers */
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
        /** @var array<Reference> udi Unique device identifier */
        public array $udi = [],
        /** @var CodeableConcept|null bodySite Anatomical location */
        public ?CodeableConcept $bodySite = null,
        /** @var array<CodeableConcept> subSite Anatomical sub-location */
        public array $subSite = [],
        /** @var array<Reference> encounter Encounters related to this billed item */
        public array $encounter = [],
        /** @var array<PositiveIntPrimitive> noteNumber Applicable note numbers */
        public array $noteNumber = [],
        /** @var array<ExplanationOfBenefitItemAdjudication> adjudication Adjudication details */
        public array $adjudication = [],
        /** @var array<ExplanationOfBenefitItemDetail> detail Additional items */
        public array $detail = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
