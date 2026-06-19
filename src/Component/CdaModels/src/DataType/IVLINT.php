<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\CdaModels\DataType;

use Ardenexal\FHIRTools\Component\CdaModels\Enum\SetOperator;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\LogicalModel;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRPathInvariant;

#[LogicalModel(
    url: 'http://hl7.org/cda/stds/core/StructureDefinition/IVL-INT',
    name: 'IVL_INT',
    fhirVersion: '5.0.0',
    xmlNamespace: 'urn:hl7-org:v3',
)]
#[FHIRPathInvariant(
    key: 'ivl-int-center',
    severity: 'error',
    expression: 'center.empty() or (low.empty() and high.empty())',
    human: 'Center cannot co-exist with low or high',
)]
class IVLINT extends ANY
{
    public function __construct(
        #[FhirProperty(fhirType: 'integer', propertyKind: 'scalar', isArray: false, isRequired: false, xmlSerializedName: '@value')]
        public ?int $value = null,
        #[FhirProperty(fhirType: 'code', propertyKind: 'enum', isArray: false, isRequired: false, xmlSerializedName: '@operator')]
        public ?SetOperator $operator = null,
        #[FhirProperty(
            fhirType: 'http://hl7.org/cda/stds/core/StructureDefinition/IVXB-INT',
            propertyKind: 'complex',
            isArray: false,
            isRequired: false,
        )]
        public ?IVXBINT $low = null,
        #[FhirProperty(
            fhirType: 'http://hl7.org/cda/stds/core/StructureDefinition/INT',
            propertyKind: 'complex',
            isArray: false,
            isRequired: false,
        )]
        public ?INTType $center = null,
        #[FhirProperty(
            fhirType: 'http://hl7.org/cda/stds/core/StructureDefinition/INT',
            propertyKind: 'complex',
            isArray: false,
            isRequired: false,
        )]
        public ?INTType $width = null,
        #[FhirProperty(
            fhirType: 'http://hl7.org/cda/stds/core/StructureDefinition/IVXB-INT',
            propertyKind: 'complex',
            isArray: false,
            isRequired: false,
        )]
        public ?IVXBINT $high = null,
    ) {
    }
}
