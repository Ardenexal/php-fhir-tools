<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Strength expressed in terms of a reference substance. For when the ingredient strength is additionally expressed as equivalent to the strength of some other closely related substance (e.g. salt vs. base). Reference strength represents the strength (quantitative composition) of the active moiety of the active substance. There are situations when the active substance and active moiety are different, therefore both a strength and a reference strength are needed.
 */
#[FHIRBackboneElement(parentResource: 'Ingredient', elementPath: 'Ingredient.substance.strength.referenceStrength', fhirVersion: 'R5')]
class FHIRIngredientSubstanceStrengthReferenceStrength extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRCodeableReference|null substance Relevant reference substance */
        #[NotBlank]
        public ?\FHIRCodeableReference $substance = null,
        /** @var FHIRRatio|FHIRRatioRange|FHIRQuantity|null strengthX Strength expressed in terms of a reference substance */
        #[NotBlank]
        public \FHIRRatio|\FHIRRatioRange|\FHIRQuantity|null $strengthX = null,
        /** @var FHIRString|string|null measurementPoint When strength is measured at a particular point or distance */
        public \FHIRString|string|null $measurementPoint = null,
        /** @var array<FHIRCodeableConcept> country Where the strength range applies */
        public array $country = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
