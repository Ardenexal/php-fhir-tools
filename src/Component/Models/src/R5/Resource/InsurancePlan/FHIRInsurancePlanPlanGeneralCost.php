<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRMoney;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRPositiveInt;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString;

/**
 * @description Overall costs associated with the plan.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'InsurancePlan', elementPath: 'InsurancePlan.plan.generalCost', fhirVersion: 'R5')]
class FHIRInsurancePlanPlanGeneralCost extends FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRCodeableConcept|null type Type of cost */
        public ?FHIRCodeableConcept $type = null,
        /** @var FHIRPositiveInt|null groupSize Number of enrollees */
        public ?FHIRPositiveInt $groupSize = null,
        /** @var FHIRMoney|null cost Cost value */
        public ?FHIRMoney $cost = null,
        /** @var FHIRString|string|null comment Additional cost information */
        public FHIRString|string|null $comment = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
