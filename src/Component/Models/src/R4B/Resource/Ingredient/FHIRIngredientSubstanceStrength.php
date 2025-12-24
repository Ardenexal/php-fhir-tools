<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRRatio;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRRatioRange;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRString;

/**
 * @description The quantity of substance in the unit of presentation, or in the volume (or mass) of the single pharmaceutical product or manufactured item. The allowed repetitions do not represent different strengths, but are different representations - mathematically equivalent - of a single strength.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'Ingredient', elementPath: 'Ingredient.substance.strength', fhirVersion: 'R4B')]
class FHIRIngredientSubstanceStrength extends FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRRatio|FHIRRatioRange|null presentationX The quantity of substance in the unit of presentation */
        public FHIRRatio|FHIRRatioRange|null $presentationX = null,
        /** @var FHIRString|string|null textPresentation Text of either the whole presentation strength or a part of it (rest being in Strength.presentation as a ratio) */
        public FHIRString|string|null $textPresentation = null,
        /** @var FHIRRatio|FHIRRatioRange|null concentrationX The strength per unitary volume (or mass) */
        public FHIRRatio|FHIRRatioRange|null $concentrationX = null,
        /** @var FHIRString|string|null textConcentration Text of either the whole concentration strength or a part of it (rest being in Strength.concentration as a ratio) */
        public FHIRString|string|null $textConcentration = null,
        /** @var FHIRString|string|null measurementPoint When strength is measured at a particular point or distance */
        public FHIRString|string|null $measurementPoint = null,
        /** @var array<FHIRCodeableConcept> country Where the strength range applies */
        public array $country = [],
        /** @var array<FHIRIngredientSubstanceStrengthReferenceStrength> referenceStrength Strength expressed in terms of a reference substance */
        public array $referenceStrength = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
