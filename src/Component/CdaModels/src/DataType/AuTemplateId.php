<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\CdaModels\DataType;

use Ardenexal\FHIRTools\Component\CdaModels\Enum\NullFlavor;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\LogicalModel;

#[LogicalModel(
    url: 'http://ns.electronichealth.net.au/cda/StructureDefinition/templateId',
    name: 'templateId',
    fhirVersion: '5.0.0',
    xmlNamespace: 'urn:hl7-org:v3',
    refines: 'http://hl7.org/cda/stds/core/StructureDefinition/II',
    propertyOrder: ['nullFlavor', 'assigningAuthorityName', 'displayable', 'root', 'extension'],
)]
class AuTemplateId extends II
{
    public function __construct(
        ?string $assigningAuthorityName = null,
        ?bool $displayable = null,
        ?string $root = null,
        ?string $extension = null,
        ?NullFlavor $nullFlavor = null,
    ) {
        parent::__construct(
            assigningAuthorityName: $assigningAuthorityName,
            displayable: $displayable,
            root: $root,
            extension: $extension,
            nullFlavor: $nullFlavor,
        );
    }
}
