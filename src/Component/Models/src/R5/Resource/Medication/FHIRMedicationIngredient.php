<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Identifies a particular constituent of interest in the product.
 */
#[FHIRBackboneElement(parentResource: 'Medication', elementPath: 'Medication.ingredient', fhirVersion: 'R5')]
class FHIRMedicationIngredient extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRCodeableReference|null item The ingredient (substance or medication) that the ingredient.strength relates to */
        #[NotBlank]
        public ?\FHIRCodeableReference $item = null,
        /** @var FHIRBoolean|null isActive Active ingredient indicator */
        public ?\FHIRBoolean $isActive = null,
        /** @var FHIRRatio|FHIRCodeableConcept|FHIRQuantity|null strengthX Quantity of ingredient present */
        public \FHIRRatio|\FHIRCodeableConcept|\FHIRQuantity|null $strengthX = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
