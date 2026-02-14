<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\ExplanationOfBenefit;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Balance by Benefit Category.
 */
#[FHIRBackboneElement(parentResource: 'ExplanationOfBenefit', elementPath: 'ExplanationOfBenefit.benefitBalance', fhirVersion: 'R4')]
class ExplanationOfBenefitBenefitBalance extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var CodeableConcept|null category Benefit classification */
        #[NotBlank]
        public ?CodeableConcept $category = null,
        /** @var bool|null excluded Excluded from the plan */
        public ?bool $excluded = null,
        /** @var StringPrimitive|string|null name Short name for the benefit */
        public StringPrimitive|string|null $name = null,
        /** @var StringPrimitive|string|null description Description of the benefit or services covered */
        public StringPrimitive|string|null $description = null,
        /** @var CodeableConcept|null network In or out of network */
        public ?CodeableConcept $network = null,
        /** @var CodeableConcept|null unit Individual or family */
        public ?CodeableConcept $unit = null,
        /** @var CodeableConcept|null term Annual or lifetime */
        public ?CodeableConcept $term = null,
        /** @var array<ExplanationOfBenefitBenefitBalanceFinancial> financial Benefit Summary */
        public array $financial = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
