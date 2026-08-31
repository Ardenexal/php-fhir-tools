<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\CdaModels\DataType;

use Ardenexal\FHIRTools\Component\CdaModels\Enum\NullFlavor;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\LogicalModel;

#[LogicalModel(
    url: 'http://hl7.org/cda/stds/core/StructureDefinition/IVXB-TS',
    name: 'IVXB_TS',
    fhirVersion: '5.0.0',
    xmlNamespace: 'urn:hl7-org:v3',
    propertyOrder: ['nullFlavor', 'value', 'inclusive'],
)]
class IVXBTS extends TS
{
    public function __construct(
        #[FhirProperty(fhirType: 'boolean', propertyKind: 'scalar', isArray: false, isRequired: false, xmlSerializedName: '@inclusive')]
        public ?bool $inclusive = null,
        ?string $value = null,
        ?NullFlavor $nullFlavor = null,
    ) {
        parent::__construct(
            value: $value,
            nullFlavor: $nullFlavor,
        );
    }
}
