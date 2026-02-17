<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\Goal;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Duration;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Quantity;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Range;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Ratio;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\DatePrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;

/**
 * @description Indicates what should be done by when.
 */
#[FHIRBackboneElement(parentResource: 'Goal', elementPath: 'Goal.target', fhirVersion: 'R4')]
class GoalTarget extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var CodeableConcept|null measure The parameter whose value is being tracked */
        public ?CodeableConcept $measure = null,
        /** @var Quantity|Range|CodeableConcept|StringPrimitive|string|bool|int|Ratio|null detailX The target value to be achieved */
        public Quantity|Range|CodeableConcept|StringPrimitive|string|bool|int|Ratio|null $detailX = null,
        /** @var DatePrimitive|Duration|null dueX Reach goal on or before */
        public DatePrimitive|Duration|null $dueX = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
