<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\CdaModels\DataType;

use Ardenexal\FHIRTools\Component\CdaModels\Enum\NullFlavor;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\LogicalModel;

#[LogicalModel(
    url: 'http://ns.electronichealth.net.au/cda/StructureDefinition/au-Telecom',
    name: 'TEL',
    fhirVersion: '5.0.0',
    xmlNamespace: 'urn:hl7-org:v3',
    refines: 'http://hl7.org/cda/stds/core/StructureDefinition/TEL',
    propertyOrder: ['nullFlavor', 'value', 'useablePeriod', 'use'],
)]
class AuTelecom extends TEL
{
    /**
     * @param list<IVLTS>  $useablePeriod
     * @param list<string> $use
     */
    public function __construct(
        ?string $value = null,
        array $useablePeriod = [],
        array $use = [],
        ?NullFlavor $nullFlavor = null,
    ) {
        parent::__construct(
            value: $value,
            useablePeriod: $useablePeriod,
            use: $use,
            nullFlavor: $nullFlavor,
        );
    }
}
