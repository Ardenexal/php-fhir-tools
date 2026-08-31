<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\CdaModels\DataType;

use Ardenexal\FHIRTools\Component\CdaModels\Enum\NullFlavor;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\LogicalModel;

#[LogicalModel(
    url: 'http://hl7.org/cda/stds/core/StructureDefinition/IVXB-INT',
    name: 'IVXB_INT',
    fhirVersion: '5.0.0',
    xmlNamespace: 'urn:hl7-org:v3',
    propertyOrder: ['nullFlavor', 'value', 'inclusive'],
)]
class IVXBINT extends INTType
{
    public function __construct(
        #[FhirProperty(fhirType: 'boolean', propertyKind: 'scalar', isArray: false, isRequired: false, xmlSerializedName: '@inclusive')]
        public ?bool $inclusive = null,
        ?int $value = null,
        ?NullFlavor $nullFlavor = null,
    ) {
        parent::__construct(
            value: $value,
            nullFlavor: $nullFlavor,
        );
    }
}
