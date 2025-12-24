<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableReference;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRQuantity;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRRatio;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Identifies a particular constituent of interest in the product.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'MedicationKnowledge', elementPath: 'MedicationKnowledge.definitional.ingredient', fhirVersion: 'R5')]
class FHIRMedicationKnowledgeDefinitionalIngredient extends FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRCodeableReference|null item Substances contained in the medication */
        #[NotBlank]
        public ?FHIRCodeableReference $item = null,
        /** @var FHIRCodeableConcept|null type A code that defines the type of ingredient, active, base, etc */
        public ?FHIRCodeableConcept $type = null,
        /** @var FHIRRatio|FHIRCodeableConcept|FHIRQuantity|null strengthX Quantity of ingredient present */
        public FHIRRatio|FHIRCodeableConcept|FHIRQuantity|null $strengthX = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
