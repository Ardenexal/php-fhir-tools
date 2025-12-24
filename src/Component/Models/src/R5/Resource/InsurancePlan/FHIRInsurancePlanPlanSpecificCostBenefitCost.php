<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRQuantity;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description List of the costs associated with a specific benefit.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'InsurancePlan', elementPath: 'InsurancePlan.plan.specificCost.benefit.cost', fhirVersion: 'R5')]
class FHIRInsurancePlanPlanSpecificCostBenefitCost extends FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRCodeableConcept|null type Type of cost */
        #[NotBlank]
        public ?FHIRCodeableConcept $type = null,
        /** @var FHIRCodeableConcept|null applicability in-network | out-of-network | other */
        public ?FHIRCodeableConcept $applicability = null,
        /** @var array<FHIRCodeableConcept> qualifiers Additional information about the cost */
        public array $qualifiers = [],
        /** @var FHIRQuantity|null value The actual cost value */
        public ?FHIRQuantity $value = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
