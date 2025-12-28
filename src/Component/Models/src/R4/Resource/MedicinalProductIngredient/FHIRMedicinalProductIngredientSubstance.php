<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description The ingredient substance.
 */
#[FHIRBackboneElement(parentResource: 'MedicinalProductIngredient', elementPath: 'MedicinalProductIngredient.substance', fhirVersion: 'R4')]
class FHIRMedicinalProductIngredientSubstance extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRCodeableConcept|null code The ingredient substance */
        #[NotBlank]
        public ?\FHIRCodeableConcept $code = null,
        /** @var array<FHIRMedicinalProductIngredientSpecifiedSubstanceStrength> strength Quantity of the substance or specified substance present in the manufactured item or pharmaceutical product */
        public array $strength = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
