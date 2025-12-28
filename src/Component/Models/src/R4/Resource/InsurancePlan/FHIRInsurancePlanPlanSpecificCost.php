<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Costs associated with the coverage provided by the product.
 */
#[FHIRBackboneElement(parentResource: 'InsurancePlan', elementPath: 'InsurancePlan.plan.specificCost', fhirVersion: 'R4')]
class FHIRInsurancePlanPlanSpecificCost extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRCodeableConcept|null category General category of benefit */
        #[NotBlank]
        public ?\FHIRCodeableConcept $category = null,
        /** @var array<FHIRInsurancePlanPlanSpecificCostBenefit> benefit Benefits list */
        public array $benefit = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
