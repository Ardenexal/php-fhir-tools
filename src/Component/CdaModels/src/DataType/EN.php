<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\CdaModels\DataType;

use Ardenexal\FHIRTools\Component\CdaModels\Enum\EntityNameUse;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\LogicalModel;

#[LogicalModel(
    url: 'http://hl7.org/cda/stds/core/StructureDefinition/EN',
    name: 'EN',
    fhirVersion: '5.0.0',
    xmlNamespace: 'urn:hl7-org:v3',
)]
class EN extends ANY
{
    /**
     * @param list<EntityNameUse> $use
     * @param list<string>        $item
     */
    public function __construct(
        #[FhirProperty(
            fhirType: 'code',
            propertyKind: 'enum',
            isArray: true,
            isRequired: false,
            xmlSerializedName: '@use',
            phpType: '\Ardenexal\FHIRTools\Component\CdaModels\Enum\EntityNameUse',
        )]
        public array $use = [],
        #[FhirProperty(fhirType: 'http://hl7.org/fhir/StructureDefinition/Base', propertyKind: 'scalar', isArray: true, isRequired: false)]
        public array $item = [],
        #[FhirProperty(
            fhirType: 'http://hl7.org/cda/stds/core/StructureDefinition/IVL-TS',
            propertyKind: 'complex',
            isArray: false,
            isRequired: false,
        )]
        public ?IVLTS $validTime = null,
    ) {
    }
}
