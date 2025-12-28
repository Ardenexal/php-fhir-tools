<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableReference;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRQuantity;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString;

/**
 * @description Oral nutritional products given in order to add further nutritional value to the patient's diet.
 */
#[FHIRBackboneElement(parentResource: 'NutritionOrder', elementPath: 'NutritionOrder.supplement', fhirVersion: 'R5')]
class FHIRNutritionOrderSupplement extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRCodeableReference|null type Type of supplement product requested */
        public ?FHIRCodeableReference $type = null,
        /** @var FHIRString|string|null productName Product or brand name of the nutritional supplement */
        public FHIRString|string|null $productName = null,
        /** @var FHIRNutritionOrderSupplementSchedule|null schedule Scheduling information for supplements */
        public ?FHIRNutritionOrderSupplementSchedule $schedule = null,
        /** @var FHIRQuantity|null quantity Amount of the nutritional supplement */
        public ?FHIRQuantity $quantity = null,
        /** @var FHIRString|string|null instruction Instructions or additional information about the oral supplement */
        public FHIRString|string|null $instruction = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
