<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;

/**
 * @description Details about an insurance plan.
 */
#[FHIRBackboneElement(parentResource: 'InsurancePlan', elementPath: 'InsurancePlan.plan', fhirVersion: 'R5')]
class FHIRInsurancePlanPlan extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var array<FHIRIdentifier> identifier Business Identifier for Product */
        public array $identifier = [],
        /** @var FHIRCodeableConcept|null type Type of plan */
        public ?\FHIRCodeableConcept $type = null,
        /** @var array<FHIRReference> coverageArea Where product applies */
        public array $coverageArea = [],
        /** @var array<FHIRReference> network What networks provide coverage */
        public array $network = [],
        /** @var array<FHIRInsurancePlanPlanGeneralCost> generalCost Overall costs */
        public array $generalCost = [],
        /** @var array<FHIRInsurancePlanPlanSpecificCost> specificCost Specific costs */
        public array $specificCost = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
