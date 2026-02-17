<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\NutritionOrder;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Quantity;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;

/**
 * @description Feeding provided through the gastrointestinal tract via a tube, catheter, or stoma that delivers nutrition distal to the oral cavity.
 */
#[FHIRBackboneElement(parentResource: 'NutritionOrder', elementPath: 'NutritionOrder.enteralFormula', fhirVersion: 'R4')]
class NutritionOrderEnteralFormula extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var CodeableConcept|null baseFormulaType Type of enteral or infant formula */
        public ?CodeableConcept $baseFormulaType = null,
        /** @var StringPrimitive|string|null baseFormulaProductName Product or brand name of the enteral or infant formula */
        public StringPrimitive|string|null $baseFormulaProductName = null,
        /** @var CodeableConcept|null additiveType Type of modular component to add to the feeding */
        public ?CodeableConcept $additiveType = null,
        /** @var StringPrimitive|string|null additiveProductName Product or brand name of the modular additive */
        public StringPrimitive|string|null $additiveProductName = null,
        /** @var Quantity|null caloricDensity Amount of energy per specified volume that is required */
        public ?Quantity $caloricDensity = null,
        /** @var CodeableConcept|null routeofAdministration How the formula should enter the patient's gastrointestinal tract */
        public ?CodeableConcept $routeofAdministration = null,
        /** @var array<NutritionOrderEnteralFormulaAdministration> administration Formula feeding instruction as structured data */
        public array $administration = [],
        /** @var Quantity|null maxVolumeToDeliver Upper limit on formula volume per unit of time */
        public ?Quantity $maxVolumeToDeliver = null,
        /** @var StringPrimitive|string|null administrationInstruction Formula feeding instructions expressed as text */
        public StringPrimitive|string|null $administrationInstruction = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
