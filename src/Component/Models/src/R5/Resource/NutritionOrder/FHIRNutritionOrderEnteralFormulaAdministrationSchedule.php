<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRTiming;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRBoolean;

/**
 * @description Schedule information for an enteral formula.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(
    parentResource: 'NutritionOrder',
    elementPath: 'NutritionOrder.enteralFormula.administration.schedule',
    fhirVersion: 'R5',
)]
class FHIRNutritionOrderEnteralFormulaAdministrationSchedule extends FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var array<FHIRTiming> timing Scheduled frequency of enteral formula */
        public array $timing = [],
        /** @var FHIRBoolean|null asNeeded Take 'as needed' */
        public ?FHIRBoolean $asNeeded = null,
        /** @var FHIRCodeableConcept|null asNeededFor Take 'as needed' for x */
        public ?FHIRCodeableConcept $asNeededFor = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
