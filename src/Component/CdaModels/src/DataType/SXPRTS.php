<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\CdaModels\DataType;

use Ardenexal\FHIRTools\Component\CdaModels\Enum\NullFlavor;
use Ardenexal\FHIRTools\Component\CdaModels\Enum\SetOperator;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\LogicalModel;

#[LogicalModel(
    url: 'http://hl7.org/cda/stds/core/StructureDefinition/SXPR-TS',
    name: 'SXPR_TS',
    fhirVersion: '5.0.0',
    xmlNamespace: 'urn:hl7-org:v3',
    propertyOrder: ['nullFlavor', 'value', 'operator', 'comp'],
)]
class SXPRTS extends SXCMTS
{
    /**
     * @param list<SXCMTS> $comp
     */
    public function __construct(
        #[FhirProperty(
            fhirType: 'http://hl7.org/cda/stds/core/StructureDefinition/SXCM-TS',
            propertyKind: 'complex',
            isArray: true,
            isRequired: true,
            phpType: '\Ardenexal\FHIRTools\Component\CdaModels\DataType\SXCMTS',
        )]
        public array $comp = [],
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
