<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRDuration;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRQuantity;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRRange;

/**
 * @description Indicates what should be done and within what timeframe.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'PlanDefinition', elementPath: 'PlanDefinition.goal.target', fhirVersion: 'R4')]
class FHIRPlanDefinitionGoalTarget extends FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRCodeableConcept|null measure The parameter whose value is to be tracked */
        public ?FHIRCodeableConcept $measure = null,
        /** @var FHIRQuantity|FHIRRange|FHIRCodeableConcept|null detailX The target value to be achieved */
        public FHIRQuantity|FHIRRange|FHIRCodeableConcept|null $detailX = null,
        /** @var FHIRDuration|null due Reach goal within */
        public ?FHIRDuration $due = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
