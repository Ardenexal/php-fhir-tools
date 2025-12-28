<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Who performed the intake and how they were involved.
 */
#[FHIRBackboneElement(parentResource: 'NutritionIntake', elementPath: 'NutritionIntake.performer', fhirVersion: 'R5')]
class FHIRNutritionIntakePerformer extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRCodeableConcept|null function Type of performer */
        public ?\FHIRCodeableConcept $function = null,
        /** @var FHIRReference|null actor Who performed the intake */
        #[NotBlank]
        public ?\FHIRReference $actor = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
