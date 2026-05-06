<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource\DeviceDefinition;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\CodeableReference;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Period;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Quantity;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\UsageContext;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Billing code or reference associated with the device.
 */
#[FHIRBackboneElement(parentResource: 'DeviceDefinition', elementPath: 'DeviceDefinition.chargeItem', fhirVersion: 'R5')]
class DeviceDefinitionChargeItem extends BackboneElement
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
        /** @var CodeableReference|null chargeItemCode The code or reference for the charge item */
        #[FhirProperty(fhirType: 'CodeableReference', propertyKind: 'complex', isRequired: true), NotBlank]
        public ?CodeableReference $chargeItemCode = null,
        /** @var Quantity|null count Coefficient applicable to the billing code */
        #[FhirProperty(fhirType: 'Quantity', propertyKind: 'complex', isRequired: true), NotBlank]
        public ?Quantity $count = null,
        /** @var Period|null effectivePeriod A specific time period in which this charge item applies */
        #[FhirProperty(fhirType: 'Period', propertyKind: 'complex')]
        public ?Period $effectivePeriod = null,
        /** @var array<UsageContext> useContext The context to which this charge item applies */
        #[FhirProperty(
            fhirType: 'UsageContext',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\DataType\UsageContext',
        )]
        public array $useContext = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
