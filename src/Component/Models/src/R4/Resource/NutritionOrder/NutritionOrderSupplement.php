<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\NutritionOrder;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Quantity;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Timing;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;

/**
 * @description Oral nutritional products given in order to add further nutritional value to the patient's diet.
 */
#[FHIRBackboneElement(parentResource: 'NutritionOrder', elementPath: 'NutritionOrder.supplement', fhirVersion: 'R4')]
class NutritionOrderSupplement extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var CodeableConcept|null type Type of supplement product requested */
        public ?CodeableConcept $type = null,
        /** @var StringPrimitive|string|null productName Product or brand name of the nutritional supplement */
        public StringPrimitive|string|null $productName = null,
        /** @var array<Timing> schedule Scheduled frequency of supplement */
        public array $schedule = [],
        /** @var Quantity|null quantity Amount of the nutritional supplement */
        public ?Quantity $quantity = null,
        /** @var StringPrimitive|string|null instruction Instructions or additional information about the oral supplement */
        public StringPrimitive|string|null $instruction = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
