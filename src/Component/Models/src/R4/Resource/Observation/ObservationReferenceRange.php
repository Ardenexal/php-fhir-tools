<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\Observation;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Quantity;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Range;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;

/**
 * @description Guidance on how to interpret the value by comparison to a normal or recommended range.  Multiple reference ranges are interpreted as an "OR".   In other words, to represent two distinct target populations, two `referenceRange` elements would be used.
 */
#[FHIRBackboneElement(parentResource: 'Observation', elementPath: 'Observation.referenceRange', fhirVersion: 'R4')]
class ObservationReferenceRange extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var Quantity|null low Low Range, if relevant */
        public ?Quantity $low = null,
        /** @var Quantity|null high High Range, if relevant */
        public ?Quantity $high = null,
        /** @var CodeableConcept|null type Reference range qualifier */
        public ?CodeableConcept $type = null,
        /** @var array<CodeableConcept> appliesTo Reference range population */
        public array $appliesTo = [],
        /** @var Range|null age Applicable age range, if relevant */
        public ?Range $age = null,
        /** @var StringPrimitive|string|null text Text based reference range in an observation */
        public StringPrimitive|string|null $text = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
