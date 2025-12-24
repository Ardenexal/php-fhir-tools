<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRSubstanceAmount;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRString;

/**
 * @description Todo.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'SubstancePolymer', elementPath: 'SubstancePolymer.repeat.repeatUnit', fhirVersion: 'R4B')]
class FHIRSubstancePolymerRepeatRepeatUnit extends FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRCodeableConcept|null orientationOfPolymerisation Todo */
        public ?FHIRCodeableConcept $orientationOfPolymerisation = null,
        /** @var FHIRString|string|null repeatUnit Todo */
        public FHIRString|string|null $repeatUnit = null,
        /** @var FHIRSubstanceAmount|null amount Todo */
        public ?FHIRSubstanceAmount $amount = null,
        /** @var array<FHIRSubstancePolymerRepeatRepeatUnitDegreeOfPolymerisation> degreeOfPolymerisation Todo */
        public array $degreeOfPolymerisation = [],
        /** @var array<FHIRSubstancePolymerRepeatRepeatUnitStructuralRepresentation> structuralRepresentation Todo */
        public array $structuralRepresentation = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
