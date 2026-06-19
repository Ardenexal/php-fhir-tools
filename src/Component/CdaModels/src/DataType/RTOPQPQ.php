<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\CdaModels\DataType;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\LogicalModel;

#[LogicalModel(
    url: 'http://hl7.org/cda/stds/core/StructureDefinition/RTO-PQ-PQ',
    name: 'RTO_PQ_PQ',
    fhirVersion: '5.0.0',
    xmlNamespace: 'urn:hl7-org:v3',
)]
class RTOPQPQ extends QTY
{
    public function __construct(
        #[FhirProperty(
            fhirType: 'http://hl7.org/cda/stds/core/StructureDefinition/PQ',
            propertyKind: 'complex',
            isArray: false,
            isRequired: false,
        )]
        public ?PQ $numerator = null,
        #[FhirProperty(
            fhirType: 'http://hl7.org/cda/stds/core/StructureDefinition/PQ',
            propertyKind: 'complex',
            isArray: false,
            isRequired: false,
        )]
        public ?PQ $denominator = null,
    ) {
    }
}
