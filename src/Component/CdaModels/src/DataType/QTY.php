<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\CdaModels\DataType;

use Ardenexal\FHIRTools\Component\CdaModels\Enum\NullFlavor;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\LogicalModel;

#[LogicalModel(
    url: 'http://hl7.org/cda/stds/core/StructureDefinition/QTY',
    name: 'QTY',
    fhirVersion: '5.0.0',
    xmlNamespace: 'urn:hl7-org:v3',
    propertyOrder: ['nullFlavor'],
)]
abstract class QTY extends ANY
{
    public function __construct(?NullFlavor $nullFlavor = null)
    {
        parent::__construct(
            nullFlavor: $nullFlavor,
        );
    }
}
