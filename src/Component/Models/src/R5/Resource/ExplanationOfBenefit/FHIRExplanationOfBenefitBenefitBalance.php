<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRBoolean;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Balance by Benefit Category.
 */
#[FHIRBackboneElement(parentResource: 'ExplanationOfBenefit', elementPath: 'ExplanationOfBenefit.benefitBalance', fhirVersion: 'R5')]
class FHIRExplanationOfBenefitBenefitBalance extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRCodeableConcept|null category Benefit classification */
        #[NotBlank]
        public ?FHIRCodeableConcept $category = null,
        /** @var FHIRBoolean|null excluded Excluded from the plan */
        public ?FHIRBoolean $excluded = null,
        /** @var FHIRString|string|null name Short name for the benefit */
        public FHIRString|string|null $name = null,
        /** @var FHIRString|string|null description Description of the benefit or services covered */
        public FHIRString|string|null $description = null,
        /** @var FHIRCodeableConcept|null network In or out of network */
        public ?FHIRCodeableConcept $network = null,
        /** @var FHIRCodeableConcept|null unit Individual or family */
        public ?FHIRCodeableConcept $unit = null,
        /** @var FHIRCodeableConcept|null term Annual or lifetime */
        public ?FHIRCodeableConcept $term = null,
        /** @var array<FHIRExplanationOfBenefitBenefitBalanceFinancial> financial Benefit Summary */
        public array $financial = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
