<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString;

/**
 * @description Specifies and quantifies the repeated units and their configuration.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'SubstancePolymer', elementPath: 'SubstancePolymer.repeat', fhirVersion: 'R5')]
class FHIRSubstancePolymerRepeat extends FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRString|string|null averageMolecularFormula A representation of an (average) molecular formula from a polymer */
        public FHIRString|string|null $averageMolecularFormula = null,
        /** @var FHIRCodeableConcept|null repeatUnitAmountType How the quantitative amount of Structural Repeat Units is captured (e.g. Exact, Numeric, Average) */
        public ?FHIRCodeableConcept $repeatUnitAmountType = null,
        /** @var array<FHIRSubstancePolymerRepeatRepeatUnit> repeatUnit An SRU - Structural Repeat Unit */
        public array $repeatUnit = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
