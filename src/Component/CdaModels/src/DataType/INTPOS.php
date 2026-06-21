<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\CdaModels\DataType;

use Ardenexal\FHIRTools\Component\CdaModels\Enum\NullFlavor;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\LogicalModel;

#[LogicalModel(
    url: 'http://hl7.org/cda/stds/core/StructureDefinition/INT-POS',
    name: 'INT_POS',
    fhirVersion: '5.0.0',
    xmlNamespace: 'urn:hl7-org:sdtc',
)]
class INTPOS extends INTType
{
    public function __construct(
        ?int $value = null,
        ?NullFlavor $nullFlavor = null,
    ) {
        parent::__construct(
            value: $value,
            nullFlavor: $nullFlavor,
        );
    }
}
