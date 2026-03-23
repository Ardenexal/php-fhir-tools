<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource\ChargeItemDefinition;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Extension;

/**
 * @description Group of properties which are applicable under the same conditions. If no applicability rules are established for the group, then all properties always apply.
 */
#[FHIRBackboneElement(parentResource: 'ChargeItemDefinition', elementPath: 'ChargeItemDefinition.propertyGroup', fhirVersion: 'R4B')]
class ChargeItemDefinitionPropertyGroup extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        #[FhirProperty(fhirType: 'http://hl7.org/fhirpath/System.String', propertyKind: 'scalar', xmlSerializedName: '@id')]
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        #[FhirProperty(fhirType: 'Extension', propertyKind: 'extension', isArray: true)]
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        #[FhirProperty(fhirType: 'Extension', propertyKind: 'modifierExtension', isArray: true)]
        public array $modifierExtension = [],
        /** @var array<ChargeItemDefinitionApplicability> applicability Conditions under which the priceComponent is applicable */
        #[FhirProperty(
            fhirType: 'unknown',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R4B\Resource\ChargeItemDefinition\ChargeItemDefinitionApplicability',
        )]
        public array $applicability = [],
        /** @var array<ChargeItemDefinitionPropertyGroupPriceComponent> priceComponent Components of total line item price */
        #[FhirProperty(
            fhirType: 'BackboneElement',
            propertyKind: 'backbone',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R4B\Resource\ChargeItemDefinition\ChargeItemDefinitionPropertyGroupPriceComponent',
        )]
        public array $priceComponent = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
