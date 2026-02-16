<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\MedicinalProductIngredient;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description The ingredient substance.
 */
#[FHIRBackboneElement(parentResource: 'MedicinalProductIngredient', elementPath: 'MedicinalProductIngredient.substance', fhirVersion: 'R4')]
class MedicinalProductIngredientSubstance extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var CodeableConcept|null code The ingredient substance */
        #[NotBlank]
        public ?CodeableConcept $code = null,
        /** @var array<MedicinalProductIngredientSpecifiedSubstanceStrength> strength Quantity of the substance or specified substance present in the manufactured item or pharmaceutical product */
        public array $strength = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
