<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\InsurancePlan;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Money;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\PositiveIntPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;

/**
 * @description Overall costs associated with the plan.
 */
#[FHIRBackboneElement(parentResource: 'InsurancePlan', elementPath: 'InsurancePlan.plan.generalCost', fhirVersion: 'R4')]
class InsurancePlanPlanGeneralCost extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var CodeableConcept|null type Type of cost */
        public ?CodeableConcept $type = null,
        /** @var PositiveIntPrimitive|null groupSize Number of enrollees */
        public ?PositiveIntPrimitive $groupSize = null,
        /** @var Money|null cost Cost value */
        public ?Money $cost = null,
        /** @var StringPrimitive|string|null comment Additional cost information */
        public StringPrimitive|string|null $comment = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
