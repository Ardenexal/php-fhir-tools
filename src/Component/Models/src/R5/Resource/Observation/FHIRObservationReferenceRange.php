<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRQuantity;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRRange;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRMarkdown;

/**
 * @description Guidance on how to interpret the value by comparison to a normal or recommended range.  Multiple reference ranges are interpreted as an "OR".   In other words, to represent two distinct target populations, two `referenceRange` elements would be used.
 */
#[FHIRBackboneElement(parentResource: 'Observation', elementPath: 'Observation.referenceRange', fhirVersion: 'R5')]
class FHIRObservationReferenceRange extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRQuantity|null low Low Range, if relevant */
        public ?FHIRQuantity $low = null,
        /** @var FHIRQuantity|null high High Range, if relevant */
        public ?FHIRQuantity $high = null,
        /** @var FHIRCodeableConcept|null normalValue Normal value, if relevant */
        public ?FHIRCodeableConcept $normalValue = null,
        /** @var FHIRCodeableConcept|null type Reference range qualifier */
        public ?FHIRCodeableConcept $type = null,
        /** @var array<FHIRCodeableConcept> appliesTo Reference range population */
        public array $appliesTo = [],
        /** @var FHIRRange|null age Applicable age range, if relevant */
        public ?FHIRRange $age = null,
        /** @var FHIRMarkdown|null text Text based reference range in an observation */
        public ?FHIRMarkdown $text = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
