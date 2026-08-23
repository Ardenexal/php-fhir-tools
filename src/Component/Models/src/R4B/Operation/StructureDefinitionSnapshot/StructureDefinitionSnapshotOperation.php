<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Operation\StructureDefinitionSnapshot;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperation;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\OperationOutputShape;
use Ardenexal\FHIRTools\Component\Models\R4B\Resource\StructureDefinitionResource;

#[FhirOperation(
    code: 'snapshot',
    url: 'http://hl7.org/fhir/OperationDefinition/StructureDefinition-snapshot',
    version: 'R4B',
    inputClass: StructureDefinitionSnapshotInput::class,
    outputShape: OperationOutputShape::BareResource,
    outputClass: StructureDefinitionResource::class,
    resource: ['StructureDefinition'],
    instance: true,
    type: true,
    system: false,
)]
final class StructureDefinitionSnapshotOperation
{
}
