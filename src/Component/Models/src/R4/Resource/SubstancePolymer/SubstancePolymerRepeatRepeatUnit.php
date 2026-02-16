<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\SubstancePolymer;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\SubstanceAmount;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;

/**
 * @description Todo.
 */
#[FHIRBackboneElement(parentResource: 'SubstancePolymer', elementPath: 'SubstancePolymer.repeat.repeatUnit', fhirVersion: 'R4')]
class SubstancePolymerRepeatRepeatUnit extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var CodeableConcept|null orientationOfPolymerisation Todo */
        public ?CodeableConcept $orientationOfPolymerisation = null,
        /** @var StringPrimitive|string|null repeatUnit Todo */
        public StringPrimitive|string|null $repeatUnit = null,
        /** @var SubstanceAmount|null amount Todo */
        public ?SubstanceAmount $amount = null,
        /** @var array<SubstancePolymerRepeatRepeatUnitDegreeOfPolymerisation> degreeOfPolymerisation Todo */
        public array $degreeOfPolymerisation = [],
        /** @var array<SubstancePolymerRepeatRepeatUnitStructuralRepresentation> structuralRepresentation Todo */
        public array $structuralRepresentation = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
