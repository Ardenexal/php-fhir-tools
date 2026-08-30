<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\CdaModels\DataType;

use Ardenexal\FHIRTools\Component\CdaModels\Enum\NullFlavor;
use Ardenexal\FHIRTools\Component\CdaModels\Enum\SetOperator;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\LogicalModel;

#[LogicalModel(
    url: 'http://hl7.org/cda/stds/core/StructureDefinition/PIVL-TS',
    name: 'PIVL_TS',
    fhirVersion: '5.0.0',
    xmlNamespace: 'urn:hl7-org:v3',
)]
class PIVLTS extends SXCMTS
{
    public function __construct(
        #[FhirProperty(
            fhirType: 'http://hl7.org/cda/stds/core/StructureDefinition/IVL-TS',
            propertyKind: 'complex',
            isArray: false,
            isRequired: false,
        )]
        public ?IVLTS $phase = null,
        #[FhirProperty(
            fhirType: 'http://hl7.org/cda/stds/core/StructureDefinition/PQ',
            propertyKind: 'complex',
            isArray: false,
            isRequired: false,
        )]
        public ?PQ $period = null,
        #[FhirProperty(fhirType: 'code', propertyKind: 'scalar', isArray: false, isRequired: false, xmlSerializedName: '@alignment')]
        public ?string $alignment = null,
        #[FhirProperty(
            fhirType: 'boolean',
            propertyKind: 'scalar',
            isArray: false,
            isRequired: false,
            xmlSerializedName: '@institutionSpecified',
        )]
        public ?bool $institutionSpecified = null,
        ?SetOperator $operator = null,
        ?string $value = null,
        ?NullFlavor $nullFlavor = null,
    ) {
        parent::__construct(
            operator: $operator,
            value: $value,
            nullFlavor: $nullFlavor,
        );
    }
}
