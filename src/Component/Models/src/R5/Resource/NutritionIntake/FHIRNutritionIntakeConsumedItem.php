<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description What food or fluid product or item was consumed.
 */
#[FHIRBackboneElement(parentResource: 'NutritionIntake', elementPath: 'NutritionIntake.consumedItem', fhirVersion: 'R5')]
class FHIRNutritionIntakeConsumedItem extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRCodeableConcept|null type The type of food or fluid product */
        #[NotBlank]
        public ?\FHIRCodeableConcept $type = null,
        /** @var FHIRCodeableReference|null nutritionProduct Code that identifies the food or fluid product that was consumed */
        #[NotBlank]
        public ?\FHIRCodeableReference $nutritionProduct = null,
        /** @var FHIRTiming|null schedule Scheduled frequency of consumption */
        public ?\FHIRTiming $schedule = null,
        /** @var FHIRQuantity|null amount Quantity of the specified food */
        public ?\FHIRQuantity $amount = null,
        /** @var FHIRQuantity|null rate Rate at which enteral feeding was administered */
        public ?\FHIRQuantity $rate = null,
        /** @var FHIRBoolean|null notConsumed Flag to indicate if the food or fluid item was refused or otherwise not consumed */
        public ?\FHIRBoolean $notConsumed = null,
        /** @var FHIRCodeableConcept|null notConsumedReason Reason food or fluid was not consumed */
        public ?\FHIRCodeableConcept $notConsumedReason = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
