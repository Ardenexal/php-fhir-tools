<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\CdaModels\DataType;

use Ardenexal\FHIRTools\Component\CdaModels\Enum\NullFlavor;
use Ardenexal\FHIRTools\Component\CdaModels\Enum\SetOperator;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\LogicalModel;

#[LogicalModel(
    url: 'http://hl7.org/cda/stds/core/StructureDefinition/EIVL-TS',
    name: 'EIVL_TS',
    fhirVersion: '5.0.0',
    xmlNamespace: 'urn:hl7-org:v3',
    propertyOrder: ['nullFlavor', 'value', 'operator', 'event', 'offset'],
)]
class EIVLTS extends SXCMTS
{
    public function __construct(
        #[FhirProperty(
            fhirType: 'http://hl7.org/cda/stds/core/StructureDefinition/CV',
            propertyKind: 'complex',
            isArray: false,
            isRequired: false,
        )]
        public ?CV $event = null,
        #[FhirProperty(
            fhirType: 'http://hl7.org/cda/stds/core/StructureDefinition/IVL-PQ',
            propertyKind: 'complex',
            isArray: false,
            isRequired: false,
        )]
        public ?IVLPQ $offset = null,
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
