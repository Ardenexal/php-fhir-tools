<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRRatio;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRString;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Quantity of the substance or specified substance present in the manufactured item or pharmaceutical product.
 */
#[FHIRBackboneElement(
    parentResource: 'MedicinalProductIngredient',
    elementPath: 'MedicinalProductIngredient.specifiedSubstance.strength',
    fhirVersion: 'R4B',
)]
class FHIRMedicinalProductIngredientSpecifiedSubstanceStrength extends \Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRRatio|null presentation The quantity of substance in the unit of presentation, or in the volume (or mass) of the single pharmaceutical product or manufactured item */
        #[NotBlank]
        public ?FHIRRatio $presentation = null,
        /** @var FHIRRatio|null presentationLowLimit A lower limit for the quantity of substance in the unit of presentation. For use when there is a range of strengths, this is the lower limit, with the presentation attribute becoming the upper limit */
        public ?FHIRRatio $presentationLowLimit = null,
        /** @var FHIRRatio|null concentration The strength per unitary volume (or mass) */
        public ?FHIRRatio $concentration = null,
        /** @var FHIRRatio|null concentrationLowLimit A lower limit for the strength per unitary volume (or mass), for when there is a range. The concentration attribute then becomes the upper limit */
        public ?FHIRRatio $concentrationLowLimit = null,
        /** @var FHIRString|string|null measurementPoint For when strength is measured at a particular point or distance */
        public FHIRString|string|null $measurementPoint = null,
        /** @var array<FHIRCodeableConcept> country The country or countries for which the strength range applies */
        public array $country = [],
        /** @var array<FHIRMedicinalProductIngredientSpecifiedSubstanceStrengthReferenceStrength> referenceStrength Strength expressed in terms of a reference substance */
        public array $referenceStrength = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
