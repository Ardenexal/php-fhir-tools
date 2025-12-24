<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRInteger;

/**
 * @description Applies to homopolymer and block co-polymers where the degree of polymerisation within a block can be described.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(
    parentResource: 'SubstancePolymer',
    elementPath: 'SubstancePolymer.repeat.repeatUnit.degreeOfPolymerisation',
    fhirVersion: 'R5',
)]
class FHIRSubstancePolymerRepeatRepeatUnitDegreeOfPolymerisation extends FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRCodeableConcept|null type The type of the degree of polymerisation shall be described, e.g. SRU/Polymer Ratio */
        public ?FHIRCodeableConcept $type = null,
        /** @var FHIRInteger|null average An average amount of polymerisation */
        public ?FHIRInteger $average = null,
        /** @var FHIRInteger|null low A low expected limit of the amount */
        public ?FHIRInteger $low = null,
        /** @var FHIRInteger|null high A high expected limit of the amount */
        public ?FHIRInteger $high = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
