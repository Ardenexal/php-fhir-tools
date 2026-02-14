<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\InsurancePlan;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Quantity;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description List of the costs associated with a specific benefit.
 */
#[FHIRBackboneElement(parentResource: 'InsurancePlan', elementPath: 'InsurancePlan.plan.specificCost.benefit.cost', fhirVersion: 'R4')]
class InsurancePlanPlanSpecificCostBenefitCost extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var CodeableConcept|null type Type of cost */
        #[NotBlank]
        public ?CodeableConcept $type = null,
        /** @var CodeableConcept|null applicability in-network | out-of-network | other */
        public ?CodeableConcept $applicability = null,
        /** @var array<CodeableConcept> qualifiers Additional information about the cost */
        public array $qualifiers = [],
        /** @var Quantity|null value The actual cost value */
        public ?Quantity $value = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
