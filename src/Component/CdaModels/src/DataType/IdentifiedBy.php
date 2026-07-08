<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\CdaModels\DataType;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\LogicalModel;

#[LogicalModel(
    url: 'http://hl7.org/cda/stds/core/StructureDefinition/IdentifiedBy',
    name: 'IdentifiedBy',
    fhirVersion: '5.0.0',
    xmlNamespace: 'urn:hl7-org:sdtc',
)]
class IdentifiedBy
{
    public function __construct(
        #[FhirProperty(
            fhirType: 'code',
            propertyKind: 'scalar',
            isArray: false,
            isRequired: true,
            xmlSerializedName: '@typeCode',
            xmlNamespace: 'urn:hl7-org:v3',
        )]
        public string $typeCode = 'REL',
        #[FhirProperty(
            fhirType: 'http://hl7.org/cda/stds/core/StructureDefinition/AlternateIdentification',
            propertyKind: 'complex',
            isArray: false,
            isRequired: true,
        )]
        public ?AlternateIdentification $sdtcAlternateIdentification = null,
    ) {
    }
}
