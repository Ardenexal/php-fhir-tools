<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\CdaModels\DataType;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\LogicalModel;

#[LogicalModel(
    url: 'http://hl7.org/cda/stds/core/StructureDefinition/AlternateIdentification',
    name: 'AlternateIdentification',
    fhirVersion: '5.0.0',
    xmlNamespace: 'urn:hl7-org:v3',
    propertyOrder: ['classCode', 'id', 'code', 'statusCode', 'effectiveTime'],
)]
class AlternateIdentification
{
    public function __construct(
        #[FhirProperty(fhirType: 'code', propertyKind: 'scalar', isArray: false, isRequired: true, xmlSerializedName: '@classCode')]
        public string $classCode = 'IDENT',
        #[FhirProperty(
            fhirType: 'http://hl7.org/cda/stds/core/StructureDefinition/II',
            propertyKind: 'complex',
            isArray: false,
            isRequired: true,
            xmlNamespace: 'urn:hl7-org:sdtc',
        )]
        public ?II $id = null,
        #[FhirProperty(
            fhirType: 'http://hl7.org/cda/stds/core/StructureDefinition/CD',
            propertyKind: 'complex',
            isArray: false,
            isRequired: false,
            xmlNamespace: 'urn:hl7-org:sdtc',
        )]
        public ?CD $code = null,
        #[FhirProperty(
            fhirType: 'http://hl7.org/cda/stds/core/StructureDefinition/CS',
            propertyKind: 'complex',
            isArray: false,
            isRequired: false,
            xmlNamespace: 'urn:hl7-org:sdtc',
        )]
        public ?CS $statusCode = null,
        #[FhirProperty(
            fhirType: 'http://hl7.org/cda/stds/core/StructureDefinition/IVL-TS',
            propertyKind: 'complex',
            isArray: false,
            isRequired: false,
            xmlNamespace: 'urn:hl7-org:sdtc',
        )]
        public ?IVLTS $effectiveTime = null,
    ) {
    }
}
