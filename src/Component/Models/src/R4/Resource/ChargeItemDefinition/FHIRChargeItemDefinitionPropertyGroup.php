<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;

/**
 * @description Group of properties which are applicable under the same conditions. If no applicability rules are established for the group, then all properties always apply.
 */
#[FHIRBackboneElement(parentResource: 'ChargeItemDefinition', elementPath: 'ChargeItemDefinition.propertyGroup', fhirVersion: 'R4')]
class FHIRChargeItemDefinitionPropertyGroup extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var array<FHIRChargeItemDefinitionApplicability> applicability Conditions under which the priceComponent is applicable */
        public array $applicability = [],
        /** @var array<FHIRChargeItemDefinitionPropertyGroupPriceComponent> priceComponent Components of total line item price */
        public array $priceComponent = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
