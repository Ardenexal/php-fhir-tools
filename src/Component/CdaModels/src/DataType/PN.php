<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\CdaModels\DataType;

use Ardenexal\FHIRTools\Component\CdaModels\Enum\EntityNameUse;
use Ardenexal\FHIRTools\Component\CdaModels\Enum\NullFlavor;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\LogicalModel;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRPathInvariant;
use Ardenexal\FHIRTools\Component\Metadata\ChoiceGroupItem;

#[LogicalModel(
    url: 'http://hl7.org/cda/stds/core/StructureDefinition/PN',
    name: 'PN',
    fhirVersion: '5.0.0',
    xmlNamespace: 'urn:hl7-org:v3',
    propertyOrder: ['nullFlavor', 'use', 'item', 'validTime'],
)]
#[FHIRPathInvariant(
    key: 'pn-no-ls',
    severity: 'error',
    expression: '(item.delimiter | item.family | item.given | item.prefix | item.suffix).where(qualifier.where($this = \'LS\').exists()).empty()',
    human: 'No PN name part may have a qualifier of LS.',
)]
class PN extends EN
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
