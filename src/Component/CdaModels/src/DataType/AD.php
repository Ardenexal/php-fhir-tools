<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\CdaModels\DataType;

use Ardenexal\FHIRTools\Component\CdaModels\Enum\PostalAddressUse;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\LogicalModel;

#[LogicalModel(
    url: 'http://hl7.org/cda/stds/core/StructureDefinition/AD',
    name: 'AD',
    fhirVersion: '5.0.0',
    xmlNamespace: 'urn:hl7-org:v3',
)]
class AD extends ANY
{
    /**
     * @param list<PostalAddressUse> $use
     * @param list<string>           $item
     * @param list<IVLTS>            $useablePeriod
     */
    public function __construct(
        #[FhirProperty(fhirType: 'boolean', propertyKind: 'scalar', isArray: false, isRequired: false, xmlSerializedName: '@isNotOrdered')]
        public ?bool $isNotOrdered = null,
        #[FhirProperty(
            fhirType: 'code',
            propertyKind: 'enum',
            isArray: true,
            isRequired: false,
            xmlSerializedName: '@use',
            phpType: '\Ardenexal\FHIRTools\Component\CdaModels\Enum\PostalAddressUse',
        )]
        public array $use = [],
        #[FhirProperty(fhirType: 'http://hl7.org/fhir/StructureDefinition/Base', propertyKind: 'scalar', isArray: true, isRequired: false)]
        public array $item = [],
        #[FhirProperty(
            fhirType: 'http://hl7.org/cda/stds/core/StructureDefinition/IVL-TS',
            propertyKind: 'complex',
            isArray: true,
            isRequired: false,
            phpType: '\Ardenexal\FHIRTools\Component\CdaModels\DataType\IVLTS',
        )]
        public array $useablePeriod = [],
    ) {
    }
}
