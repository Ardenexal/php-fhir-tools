<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\CdaModels\DataType;

use Ardenexal\FHIRTools\Component\CdaModels\Enum\EntityNameUse;
use Ardenexal\FHIRTools\Component\CdaModels\Enum\NullFlavor;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\LogicalModel;
use Ardenexal\FHIRTools\Component\Metadata\ChoiceGroupItem;

#[LogicalModel(
    url: 'http://ns.electronichealth.net.au/cda/StructureDefinition/au-OrganizationName',
    name: 'au-OrganizationName',
    fhirVersion: '5.0.0',
    xmlNamespace: 'urn:hl7-org:v3',
    refines: 'http://hl7.org/cda/stds/core/StructureDefinition/ON',
    propertyOrder: ['nullFlavor', 'use', 'item', 'validTime'],
)]
class AuOrganizationName extends ON
{
    /**
     * @param list<EntityNameUse>   $use
     * @param list<ChoiceGroupItem> $item
     */
    public function __construct(
        array $use = [],
        array $item = [],
        ?IVLTS $validTime = null,
        ?NullFlavor $nullFlavor = null,
    ) {
        parent::__construct(
            use: $use,
            item: $item,
            validTime: $validTime,
            nullFlavor: $nullFlavor,
        );
    }
}
