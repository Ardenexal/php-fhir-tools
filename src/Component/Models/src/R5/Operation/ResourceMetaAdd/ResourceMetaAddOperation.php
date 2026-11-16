<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Operation\ResourceMetaAdd;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperation;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\OperationOutputShape;

#[FhirOperation(
    code: 'meta-add',
    url: 'http://hl7.org/fhir/OperationDefinition/Resource-meta-add',
    version: 'R5',
    inputClass: ResourceMetaAddInput::class,
    outputShape: OperationOutputShape::Parameters,
    outputClass: ResourceMetaAddOutput::class,
    resource: ['Resource'],
    instance: true,
    type: false,
    system: false,
)]
final class ResourceMetaAddOperation
{
}
