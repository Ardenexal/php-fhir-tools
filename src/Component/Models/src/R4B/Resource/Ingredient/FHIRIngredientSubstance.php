<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCodeableReference;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description The substance that comprises this ingredient.
 */
#[FHIRBackboneElement(parentResource: 'Ingredient', elementPath: 'Ingredient.substance', fhirVersion: 'R4B')]
class FHIRIngredientSubstance extends \Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRCodeableReference|null code A code or full resource that represents the ingredient substance */
        #[NotBlank]
        public ?FHIRCodeableReference $code = null,
        /** @var array<FHIRIngredientSubstanceStrength> strength The quantity of substance, per presentation, or per volume or mass, and type of quantity */
        public array $strength = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
