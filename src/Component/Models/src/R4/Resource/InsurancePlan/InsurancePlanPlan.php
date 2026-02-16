<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\InsurancePlan;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference;

/**
 * @description Details about an insurance plan.
 */
#[FHIRBackboneElement(parentResource: 'InsurancePlan', elementPath: 'InsurancePlan.plan', fhirVersion: 'R4')]
class InsurancePlanPlan extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var array<Identifier> identifier Business Identifier for Product */
        public array $identifier = [],
        /** @var CodeableConcept|null type Type of plan */
        public ?CodeableConcept $type = null,
        /** @var array<Reference> coverageArea Where product applies */
        public array $coverageArea = [],
        /** @var array<Reference> network What networks provide coverage */
        public array $network = [],
        /** @var array<InsurancePlanPlanGeneralCost> generalCost Overall costs */
        public array $generalCost = [],
        /** @var array<InsurancePlanPlanSpecificCost> specificCost Specific costs */
        public array $specificCost = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
