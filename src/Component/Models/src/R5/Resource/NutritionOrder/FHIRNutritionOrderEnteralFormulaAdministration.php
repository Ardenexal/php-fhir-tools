<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRQuantity;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRRatio;

/**
 * @description Formula administration instructions as structured data.  This repeating structure allows for changing the administration rate or volume over time for both bolus and continuous feeding.  An example of this would be an instruction to increase the rate of continuous feeding every 2 hours.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'NutritionOrder', elementPath: 'NutritionOrder.enteralFormula.administration', fhirVersion: 'R5')]
class FHIRNutritionOrderEnteralFormulaAdministration extends FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRNutritionOrderEnteralFormulaAdministrationSchedule|null schedule Scheduling information for enteral formula products */
        public ?FHIRNutritionOrderEnteralFormulaAdministrationSchedule $schedule = null,
        /** @var FHIRQuantity|null quantity The volume of formula to provide */
        public ?FHIRQuantity $quantity = null,
        /** @var FHIRQuantity|FHIRRatio|null rateX Speed with which the formula is provided per period of time */
        public FHIRQuantity|FHIRRatio|null $rateX = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
