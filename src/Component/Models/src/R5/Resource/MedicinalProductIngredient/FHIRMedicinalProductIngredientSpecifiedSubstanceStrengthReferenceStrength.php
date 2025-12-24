<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRRatio;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Strength expressed in terms of a reference substance.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(
    parentResource: 'MedicinalProductIngredient',
    elementPath: 'MedicinalProductIngredient.specifiedSubstance.strength.referenceStrength',
    fhirVersion: 'R5',
)]
class FHIRMedicinalProductIngredientSpecifiedSubstanceStrengthReferenceStrength extends FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRCodeableConcept|null substance Relevant reference substance */
        public ?FHIRCodeableConcept $substance = null,
        /** @var FHIRRatio|null strength Strength expressed in terms of a reference substance */
        #[NotBlank]
        public ?FHIRRatio $strength = null,
        /** @var FHIRRatio|null strengthLowLimit Strength expressed in terms of a reference substance */
        public ?FHIRRatio $strengthLowLimit = null,
        /** @var FHIRString|string|null measurementPoint For when strength is measured at a particular point or distance */
        public FHIRString|string|null $measurementPoint = null,
        /** @var array<FHIRCodeableConcept> country The country or countries for which the strength range applies */
        public array $country = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
