<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableReference;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRQuantity;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRMarkdown;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString;

/**
 * @description Feeding provided through the gastrointestinal tract via a tube, catheter, or stoma that delivers nutrition distal to the oral cavity.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'NutritionOrder', elementPath: 'NutritionOrder.enteralFormula', fhirVersion: 'R5')]
class FHIRNutritionOrderEnteralFormula extends FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRCodeableReference|null baseFormulaType Type of enteral or infant formula */
        public ?FHIRCodeableReference $baseFormulaType = null,
        /** @var FHIRString|string|null baseFormulaProductName Product or brand name of the enteral or infant formula */
        public FHIRString|string|null $baseFormulaProductName = null,
        /** @var array<FHIRCodeableReference> deliveryDevice Intended type of device for the administration */
        public array $deliveryDevice = [],
        /** @var array<FHIRNutritionOrderEnteralFormulaAdditive> additive Components to add to the feeding */
        public array $additive = [],
        /** @var FHIRQuantity|null caloricDensity Amount of energy per specified volume that is required */
        public ?FHIRQuantity $caloricDensity = null,
        /** @var FHIRCodeableConcept|null routeOfAdministration How the formula should enter the patient's gastrointestinal tract */
        public ?FHIRCodeableConcept $routeOfAdministration = null,
        /** @var array<FHIRNutritionOrderEnteralFormulaAdministration> administration Formula feeding instruction as structured data */
        public array $administration = [],
        /** @var FHIRQuantity|null maxVolumeToDeliver Upper limit on formula volume per unit of time */
        public ?FHIRQuantity $maxVolumeToDeliver = null,
        /** @var FHIRMarkdown|null administrationInstruction Formula feeding instructions expressed as text */
        public ?FHIRMarkdown $administrationInstruction = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
