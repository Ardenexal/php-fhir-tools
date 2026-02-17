<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\MedicinalProductIngredient;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Ratio;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Quantity of the substance or specified substance present in the manufactured item or pharmaceutical product.
 */
#[FHIRBackboneElement(
    parentResource: 'MedicinalProductIngredient',
    elementPath: 'MedicinalProductIngredient.specifiedSubstance.strength',
    fhirVersion: 'R4',
)]
class MedicinalProductIngredientSpecifiedSubstanceStrength extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var Ratio|null presentation The quantity of substance in the unit of presentation, or in the volume (or mass) of the single pharmaceutical product or manufactured item */
        #[NotBlank]
        public ?Ratio $presentation = null,
        /** @var Ratio|null presentationLowLimit A lower limit for the quantity of substance in the unit of presentation. For use when there is a range of strengths, this is the lower limit, with the presentation attribute becoming the upper limit */
        public ?Ratio $presentationLowLimit = null,
        /** @var Ratio|null concentration The strength per unitary volume (or mass) */
        public ?Ratio $concentration = null,
        /** @var Ratio|null concentrationLowLimit A lower limit for the strength per unitary volume (or mass), for when there is a range. The concentration attribute then becomes the upper limit */
        public ?Ratio $concentrationLowLimit = null,
        /** @var StringPrimitive|string|null measurementPoint For when strength is measured at a particular point or distance */
        public StringPrimitive|string|null $measurementPoint = null,
        /** @var array<CodeableConcept> country The country or countries for which the strength range applies */
        public array $country = [],
        /** @var array<MedicinalProductIngredientSpecifiedSubstanceStrengthReferenceStrength> referenceStrength Strength expressed in terms of a reference substance */
        public array $referenceStrength = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
