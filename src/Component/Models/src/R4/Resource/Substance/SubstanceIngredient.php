<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\Substance;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Ratio;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description A substance can be composed of other substances.
 */
#[FHIRBackboneElement(parentResource: 'Substance', elementPath: 'Substance.ingredient', fhirVersion: 'R4')]
class SubstanceIngredient extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var Ratio|null quantity Optional amount (concentration) */
        public ?Ratio $quantity = null,
        /** @var CodeableConcept|Reference|null substanceX A component of the substance */
        #[NotBlank]
        public CodeableConcept|Reference|null $substanceX = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
