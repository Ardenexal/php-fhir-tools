<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRDuration;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRQuantity;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRRange;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRRatio;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRBoolean;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRDate;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRInteger;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRString;

/**
 * @description Indicates what should be done by when.
 */
#[FHIRBackboneElement(parentResource: 'Goal', elementPath: 'Goal.target', fhirVersion: 'R4B')]
class FHIRGoalTarget extends \Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRCodeableConcept|null measure The parameter whose value is being tracked */
        public ?FHIRCodeableConcept $measure = null,
        /** @var FHIRQuantity|FHIRRange|FHIRCodeableConcept|FHIRString|string|FHIRBoolean|FHIRInteger|FHIRRatio|null detailX The target value to be achieved */
        public FHIRQuantity|FHIRRange|FHIRCodeableConcept|FHIRString|string|FHIRBoolean|FHIRInteger|FHIRRatio|null $detailX = null,
        /** @var FHIRDate|FHIRDuration|null dueX Reach goal on or before */
        public FHIRDate|FHIRDuration|null $dueX = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
