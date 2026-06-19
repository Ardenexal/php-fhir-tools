<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\CdaModels\DataType;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\LogicalModel;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRPathInvariant;

#[LogicalModel(
    url: 'http://hl7.org/cda/stds/core/StructureDefinition/PN',
    name: 'PN',
    fhirVersion: '5.0.0',
    xmlNamespace: 'urn:hl7-org:v3',
)]
#[FHIRPathInvariant(
    key: 'pn-no-ls',
    severity: 'error',
    expression: '(item.delimiter | item.family | item.given | item.prefix | item.suffix).where(qualifier.where($this = \'LS\').exists()).empty()',
    human: 'No PN name part may have a qualifier of LS.',
)]
class PN extends EN
{
    public function __construct()
    {
    }
}
