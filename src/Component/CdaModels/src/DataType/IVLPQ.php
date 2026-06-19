<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\CdaModels\DataType;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\LogicalModel;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRPathInvariant;

#[LogicalModel(
    url: 'http://hl7.org/cda/stds/core/StructureDefinition/IVL-PQ',
    name: 'IVL_PQ',
    fhirVersion: '5.0.0',
    xmlNamespace: 'urn:hl7-org:v3',
)]
#[FHIRPathInvariant(
    key: 'ivl-pq-center',
    severity: 'error',
    expression: 'center.empty() or (low.empty() and high.empty())',
    human: 'Center cannot co-exist with low or high',
)]
class IVLPQ extends PQ
{
    public function __construct(
        #[FhirProperty(fhirType: 'code', propertyKind: 'scalar', isArray: false, isRequired: false, xmlSerializedName: '@operator')]
        public ?string $operator = null,
        #[FhirProperty(
            fhirType: 'http://hl7.org/cda/stds/core/StructureDefinition/IVXB-PQ',
            propertyKind: 'complex',
            isArray: false,
            isRequired: false,
        )]
        public ?IVXBPQ $low = null,
        #[FhirProperty(
            fhirType: 'http://hl7.org/cda/stds/core/StructureDefinition/PQ',
            propertyKind: 'complex',
            isArray: false,
            isRequired: false,
        )]
        public ?PQ $center = null,
        #[FhirProperty(
            fhirType: 'http://hl7.org/cda/stds/core/StructureDefinition/PQ',
            propertyKind: 'complex',
            isArray: false,
            isRequired: false,
        )]
        public ?PQ $width = null,
        #[FhirProperty(
            fhirType: 'http://hl7.org/cda/stds/core/StructureDefinition/IVXB-PQ',
            propertyKind: 'complex',
            isArray: false,
            isRequired: false,
        )]
        public ?IVXBPQ $high = null,
    ) {
    }
}
