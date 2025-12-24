<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRRatio;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description A substance can be composed of other substances.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'Substance', elementPath: 'Substance.ingredient', fhirVersion: 'R5')]
class FHIRSubstanceIngredient extends FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRRatio|null quantity Optional amount (concentration) */
        public ?FHIRRatio $quantity = null,
        /** @var FHIRCodeableConcept|FHIRReference|null substanceX A component of the substance */
        #[NotBlank]
        public FHIRCodeableConcept|FHIRReference|null $substanceX = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
