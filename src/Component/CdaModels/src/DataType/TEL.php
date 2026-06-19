<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\CdaModels\DataType;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\LogicalModel;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRPathInvariant;

#[LogicalModel(
    url: 'http://hl7.org/cda/stds/core/StructureDefinition/TEL',
    name: 'TEL',
    fhirVersion: '5.0.0',
    xmlNamespace: 'urn:hl7-org:v3',
)]
#[FHIRPathInvariant(
    key: 'value-null',
    severity: 'error',
    expression: '(value | nullFlavor).count() = 1',
    human: 'value and nullFlavor are mutually exclusive (one must be present)',
)]
class TEL extends ANY
{
    /**
     * @param list<IVLTS>  $useablePeriod
     * @param list<string> $use
     */
    public function __construct(
        #[FhirProperty(fhirType: 'url', propertyKind: 'scalar', isArray: false, isRequired: false, xmlSerializedName: '@value')]
        public ?string $value = null,
        #[FhirProperty(
            fhirType: 'http://hl7.org/cda/stds/core/StructureDefinition/IVL-TS',
            propertyKind: 'complex',
            isArray: true,
            isRequired: false,
            phpType: '\Ardenexal\FHIRTools\Component\CdaModels\DataType\IVLTS',
        )]
        public array $useablePeriod = [],
        #[FhirProperty(fhirType: 'code', propertyKind: 'scalar', isArray: true, isRequired: false, xmlSerializedName: '@use')]
        public array $use = [],
    ) {
    }
}
