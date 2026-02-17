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
 * @description Strength expressed in terms of a reference substance.
 */
#[FHIRBackboneElement(
    parentResource: 'MedicinalProductIngredient',
    elementPath: 'MedicinalProductIngredient.specifiedSubstance.strength.referenceStrength',
    fhirVersion: 'R4',
)]
class MedicinalProductIngredientSpecifiedSubstanceStrengthReferenceStrength extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var CodeableConcept|null substance Relevant reference substance */
        public ?CodeableConcept $substance = null,
        /** @var Ratio|null strength Strength expressed in terms of a reference substance */
        #[NotBlank]
        public ?Ratio $strength = null,
        /** @var Ratio|null strengthLowLimit Strength expressed in terms of a reference substance */
        public ?Ratio $strengthLowLimit = null,
        /** @var StringPrimitive|string|null measurementPoint For when strength is measured at a particular point or distance */
        public StringPrimitive|string|null $measurementPoint = null,
        /** @var array<CodeableConcept> country The country or countries for which the strength range applies */
        public array $country = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
