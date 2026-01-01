<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRString;

/**
 * @description Todo.
 */
#[FHIRBackboneElement(parentResource: 'SubstancePolymer', elementPath: 'SubstancePolymer.repeat.repeatUnit', fhirVersion: 'R4')]
class FHIRSubstancePolymerRepeatRepeatUnit extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRBackboneElement
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
