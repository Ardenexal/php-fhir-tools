<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource\DeviceDefinition;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Period;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\UriPrimitive;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Indicates where and when the device is available on the market.
 */
#[FHIRBackboneElement(
    parentResource: 'DeviceDefinition',
    elementPath: 'DeviceDefinition.udiDeviceIdentifier.marketDistribution',
    fhirVersion: 'R5',
)]
class DeviceDefinitionUdiDeviceIdentifierMarketDistribution extends BackboneElement
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
        /** @var Period|null marketPeriod Begin and end dates for the commercial distribution of the device */
        #[FhirProperty(fhirType: 'Period', propertyKind: 'complex', isRequired: true), NotBlank]
        public ?Period $marketPeriod = null,
        /** @var UriPrimitive|null subJurisdiction National state or territory where the device is commercialized */
        #[FhirProperty(fhirType: 'uri', propertyKind: 'primitive', isRequired: true), NotBlank]
        public ?UriPrimitive $subJurisdiction = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
