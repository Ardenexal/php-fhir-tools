<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Operation\ResourceAdd;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperation;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\OperationOutputShape;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\AbstractResource;

#[FhirOperation(
    code: 'add',
    url: 'http://hl7.org/fhir/OperationDefinition/Resource-add',
    version: 'R5',
    inputClass: ResourceAddInput::class,
    outputShape: OperationOutputShape::BareResource,
    outputClass: AbstractResource::class,
    resource: ['Resource'],
    instance: true,
    type: false,
    system: false,
)]
final class ResourceAddOperation
{
}
