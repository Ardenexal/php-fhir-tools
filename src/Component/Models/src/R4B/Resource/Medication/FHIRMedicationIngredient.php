<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Identifies a particular constituent of interest in the product.
 */
#[FHIRBackboneElement(parentResource: 'Medication', elementPath: 'Medication.ingredient', fhirVersion: 'R4B')]
class FHIRMedicationIngredient extends \Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRCodeableConcept|FHIRReference|null itemX The actual ingredient or content */
        #[NotBlank]
        public \FHIRCodeableConcept|\FHIRReference|null $itemX = null,
        /** @var FHIRBoolean|null isActive Active ingredient indicator */
        public ?\FHIRBoolean $isActive = null,
        /** @var FHIRRatio|null strength Quantity of ingredient present */
        public ?\FHIRRatio $strength = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
