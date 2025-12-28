<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description List of the specific benefits under this category of benefit.
 */
#[FHIRBackboneElement(parentResource: 'InsurancePlan', elementPath: 'InsurancePlan.plan.specificCost.benefit', fhirVersion: 'R4B')]
class FHIRInsurancePlanPlanSpecificCostBenefit extends \Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRCodeableConcept|null type Type of specific benefit */
        #[NotBlank]
        public ?\FHIRCodeableConcept $type = null,
        /** @var array<FHIRInsurancePlanPlanSpecificCostBenefitCost> cost List of the costs */
        public array $cost = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
