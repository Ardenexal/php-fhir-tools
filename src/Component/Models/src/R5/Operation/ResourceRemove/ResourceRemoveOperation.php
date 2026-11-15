<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Operation\ResourceRemove;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperation;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\OperationOutputShape;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\AbstractResource;

#[FhirOperation(
    code: 'remove',
    url: 'http://hl7.org/fhir/OperationDefinition/Resource-remove',
    version: 'R5',
    inputClass: ResourceRemoveInput::class,
    outputShape: OperationOutputShape::BareResource,
    outputClass: AbstractResource::class,
    resource: ['Resource'],
    instance: true,
    type: false,
    system: false,
)]
final class ResourceRemoveOperation
{
}
