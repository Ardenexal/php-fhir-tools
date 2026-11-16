<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Operation\ResourceMeta;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperation;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\OperationOutputShape;

#[FhirOperation(
    code: 'meta',
    url: 'http://hl7.org/fhir/OperationDefinition/Resource-meta',
    version: 'R4',
    inputClass: '',
    outputShape: OperationOutputShape::Parameters,
    outputClass: ResourceMetaOutput::class,
    resource: ['Resource'],
    instance: true,
    type: true,
    system: true,
)]
final class ResourceMetaOperation
{
}
