<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description A substance can be composed of other substances.
 */
#[FHIRBackboneElement(parentResource: 'Substance', elementPath: 'Substance.ingredient', fhirVersion: 'R4')]
class FHIRSubstanceIngredient extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRRatio|null quantity Optional amount (concentration) */
        public ?\FHIRRatio $quantity = null,
        /** @var FHIRCodeableConcept|FHIRReference|null substanceX A component of the substance */
        #[NotBlank]
        public \FHIRCodeableConcept|\FHIRReference|null $substanceX = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
