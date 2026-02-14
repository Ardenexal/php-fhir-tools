<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\MedicinalProductIngredient;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description A specified substance that comprises this ingredient.
 */
#[FHIRBackboneElement(
    parentResource: 'MedicinalProductIngredient',
    elementPath: 'MedicinalProductIngredient.specifiedSubstance',
    fhirVersion: 'R4',
)]
class MedicinalProductIngredientSpecifiedSubstance extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var CodeableConcept|null code The specified substance */
        #[NotBlank]
        public ?CodeableConcept $code = null,
        /** @var CodeableConcept|null group The group of specified substance, e.g. group 1 to 4 */
        #[NotBlank]
        public ?CodeableConcept $group = null,
        /** @var CodeableConcept|null confidentiality Confidentiality level of the specified substance as the ingredient */
        public ?CodeableConcept $confidentiality = null,
        /** @var array<MedicinalProductIngredientSpecifiedSubstanceStrength> strength Quantity of the substance or specified substance present in the manufactured item or pharmaceutical product */
        public array $strength = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
