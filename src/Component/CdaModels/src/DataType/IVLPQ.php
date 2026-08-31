<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\CdaModels\DataType;

use Ardenexal\FHIRTools\Component\CdaModels\Enum\NullFlavor;
use Ardenexal\FHIRTools\Component\CdaModels\Enum\SetOperator;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\LogicalModel;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRPathInvariant;

#[LogicalModel(
    url: 'http://hl7.org/cda/stds/core/StructureDefinition/IVL-PQ',
    name: 'IVL_PQ',
    fhirVersion: '5.0.0',
    xmlNamespace: 'urn:hl7-org:v3',
    propertyOrder: ['nullFlavor', 'unit', 'value', 'translation', 'operator', 'low', 'center', 'width', 'high'],
)]
#[FHIRPathInvariant(
    key: 'ivl-pq-center',
    severity: 'error',
    expression: 'center.empty() or (low.empty() and high.empty())',
    human: 'Center cannot co-exist with low or high',
)]
class IVLPQ extends PQ
{
    /**
     * @param list<PQR> $translation
     */
    public function __construct(
        #[FhirProperty(fhirType: 'code', propertyKind: 'enum', isArray: false, isRequired: false, xmlSerializedName: '@operator')]
        public ?SetOperator $operator = null,
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
        ?string $unit = null,
        ?float $value = null,
        array $translation = [],
        ?NullFlavor $nullFlavor = null,
    ) {
        parent::__construct(
            unit: $unit,
            value: $value,
            translation: $translation,
            nullFlavor: $nullFlavor,
        );
    }
}
