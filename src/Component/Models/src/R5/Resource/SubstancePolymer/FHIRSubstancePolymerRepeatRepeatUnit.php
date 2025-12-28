<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;

/**
 * @description An SRU - Structural Repeat Unit.
 */
#[FHIRBackboneElement(parentResource: 'SubstancePolymer', elementPath: 'SubstancePolymer.repeat.repeatUnit', fhirVersion: 'R5')]
class FHIRSubstancePolymerRepeatRepeatUnit extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRString|string|null unit Structural repeat units are essential elements for defining polymers */
        public \FHIRString|string|null $unit = null,
        /** @var FHIRCodeableConcept|null orientation The orientation of the polymerisation, e.g. head-tail, head-head, random */
        public ?\FHIRCodeableConcept $orientation = null,
        /** @var FHIRInteger|null amount Number of repeats of this unit */
        public ?\FHIRInteger $amount = null,
        /** @var array<FHIRSubstancePolymerRepeatRepeatUnitDegreeOfPolymerisation> degreeOfPolymerisation Applies to homopolymer and block co-polymers where the degree of polymerisation within a block can be described */
        public array $degreeOfPolymerisation = [],
        /** @var array<FHIRSubstancePolymerRepeatRepeatUnitStructuralRepresentation> structuralRepresentation A graphical structure for this SRU */
        public array $structuralRepresentation = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
