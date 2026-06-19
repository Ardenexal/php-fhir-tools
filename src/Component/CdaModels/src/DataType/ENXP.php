<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\CdaModels\DataType;

use Ardenexal\FHIRTools\Component\CdaModels\Enum\EntityNamePartQualifier;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\LogicalModel;

#[LogicalModel(
    url: 'http://hl7.org/cda/stds/core/StructureDefinition/ENXP',
    name: 'ENXP',
    fhirVersion: '5.0.0',
    xmlNamespace: 'urn:hl7-org:v3',
)]
class ENXP extends ST
{
    /**
     * @param list<EntityNamePartQualifier> $qualifier
     */
    public function __construct(
        #[FhirProperty(fhirType: 'code', propertyKind: 'scalar', isArray: false, isRequired: false, xmlSerializedName: '@partType')]
        public ?string $partType = null,
        #[FhirProperty(
            fhirType: 'code',
            propertyKind: 'enum',
            isArray: true,
            isRequired: false,
            xmlSerializedName: '@qualifier',
            phpType: '\Ardenexal\FHIRTools\Component\CdaModels\Enum\EntityNamePartQualifier',
        )]
        public array $qualifier = [],
    ) {
    }
}
