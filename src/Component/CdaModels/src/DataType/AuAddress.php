<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\CdaModels\DataType;

use Ardenexal\FHIRTools\Component\CdaModels\Enum\NullFlavor;
use Ardenexal\FHIRTools\Component\CdaModels\Enum\PostalAddressUse;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\LogicalModel;

#[LogicalModel(
    url: 'http://ns.electronichealth.net.au/cda/StructureDefinition/au-Address',
    name: 'AD',
    fhirVersion: '5.0.0',
    xmlNamespace: 'urn:hl7-org:v3',
)]
class AuAddress extends AD
{
    /**
     * @param list<PostalAddressUse> $use
     * @param list<string>           $item
     * @param list<IVLTS>            $useablePeriod
     */
    public function __construct(
        ?bool $isNotOrdered = null,
        array $use = [],
        array $item = [],
        array $useablePeriod = [],
        ?NullFlavor $nullFlavor = null,
    ) {
        parent::__construct(
            isNotOrdered: $isNotOrdered,
            use: $use,
            item: $item,
            useablePeriod: $useablePeriod,
            nullFlavor: $nullFlavor,
        );
    }
}
