<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;

/**
 * @description Todo.
 */
#[FHIRBackboneElement(parentResource: 'SubstancePolymer', elementPath: 'SubstancePolymer.repeat', fhirVersion: 'R4')]
class FHIRSubstancePolymerRepeat extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRInteger|null numberOfUnits Todo */
        public ?\FHIRInteger $numberOfUnits = null,
        /** @var FHIRString|string|null averageMolecularFormula Todo */
        public \FHIRString|string|null $averageMolecularFormula = null,
        /** @var FHIRCodeableConcept|null repeatUnitAmountType Todo */
        public ?\FHIRCodeableConcept $repeatUnitAmountType = null,
        /** @var array<FHIRSubstancePolymerRepeatRepeatUnit> repeatUnit Todo */
        public array $repeatUnit = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
