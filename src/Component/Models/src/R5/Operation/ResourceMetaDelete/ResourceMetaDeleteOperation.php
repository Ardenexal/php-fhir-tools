<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Operation\ResourceMetaDelete;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperation;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\OperationOutputShape;

#[FhirOperation(
    code: 'meta-delete',
    url: 'http://hl7.org/fhir/OperationDefinition/Resource-meta-delete',
    version: 'R5',
    inputClass: ResourceMetaDeleteInput::class,
    outputShape: OperationOutputShape::Parameters,
    outputClass: ResourceMetaDeleteOutput::class,
    resource: ['Resource'],
    instance: true,
    type: false,
    system: false,
)]
final class ResourceMetaDeleteOperation
{
}
