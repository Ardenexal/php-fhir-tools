<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRQuantity;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRString;

/**
 * @description Feeding provided through the gastrointestinal tract via a tube, catheter, or stoma that delivers nutrition distal to the oral cavity.
 */
#[FHIRBackboneElement(parentResource: 'NutritionOrder', elementPath: 'NutritionOrder.enteralFormula', fhirVersion: 'R4B')]
class FHIRNutritionOrderEnteralFormula extends \Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRCodeableConcept|null baseFormulaType Type of enteral or infant formula */
        public ?FHIRCodeableConcept $baseFormulaType = null,
        /** @var FHIRString|string|null baseFormulaProductName Product or brand name of the enteral or infant formula */
        public FHIRString|string|null $baseFormulaProductName = null,
        /** @var FHIRCodeableConcept|null additiveType Type of modular component to add to the feeding */
        public ?FHIRCodeableConcept $additiveType = null,
        /** @var FHIRString|string|null additiveProductName Product or brand name of the modular additive */
        public FHIRString|string|null $additiveProductName = null,
        /** @var FHIRQuantity|null caloricDensity Amount of energy per specified volume that is required */
        public ?FHIRQuantity $caloricDensity = null,
        /** @var FHIRCodeableConcept|null routeofAdministration How the formula should enter the patient's gastrointestinal tract */
        public ?FHIRCodeableConcept $routeofAdministration = null,
        /** @var array<FHIRNutritionOrderEnteralFormulaAdministration> administration Formula feeding instruction as structured data */
        public array $administration = [],
        /** @var FHIRQuantity|null maxVolumeToDeliver Upper limit on formula volume per unit of time */
        public ?FHIRQuantity $maxVolumeToDeliver = null,
        /** @var FHIRString|string|null administrationInstruction Formula feeding instructions expressed as text */
        public FHIRString|string|null $administrationInstruction = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
