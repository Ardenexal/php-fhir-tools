<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\Medication;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Ratio;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Identifies a particular constituent of interest in the product.
 */
#[FHIRBackboneElement(parentResource: 'Medication', elementPath: 'Medication.ingredient', fhirVersion: 'R4')]
class MedicationIngredient extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var CodeableConcept|Reference|null itemX The actual ingredient or content */
        #[NotBlank]
        public CodeableConcept|Reference|null $itemX = null,
        /** @var bool|null isActive Active ingredient indicator */
        public ?bool $isActive = null,
        /** @var Ratio|null strength Quantity of ingredient present */
        public ?Ratio $strength = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
