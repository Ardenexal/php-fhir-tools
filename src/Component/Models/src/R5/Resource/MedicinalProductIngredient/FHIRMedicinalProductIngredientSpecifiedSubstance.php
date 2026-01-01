<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description A specified substance that comprises this ingredient.
 */
#[FHIRBackboneElement(
    parentResource: 'MedicinalProductIngredient',
    elementPath: 'MedicinalProductIngredient.specifiedSubstance',
    fhirVersion: 'R5',
)]
class FHIRMedicinalProductIngredientSpecifiedSubstance extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRCodeableConcept|null code The specified substance */
        #[NotBlank]
        public ?FHIRCodeableConcept $code = null,
        /** @var FHIRCodeableConcept|null group The group of specified substance, e.g. group 1 to 4 */
        #[NotBlank]
        public ?FHIRCodeableConcept $group = null,
        /** @var FHIRCodeableConcept|null confidentiality Confidentiality level of the specified substance as the ingredient */
        public ?FHIRCodeableConcept $confidentiality = null,
        /** @var array<FHIRMedicinalProductIngredientSpecifiedSubstanceStrength> strength Quantity of the substance or specified substance present in the manufactured item or pharmaceutical product */
        public array $strength = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
