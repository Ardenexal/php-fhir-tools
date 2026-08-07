<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Operation\ResourceFilter;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperation;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\OperationOutputShape;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\AbstractResource;

#[FhirOperation(
    code: 'filter',
    url: 'http://hl7.org/fhir/OperationDefinition/Resource-filter',
    version: 'R5',
    inputClass: ResourceFilterInput::class,
    outputShape: OperationOutputShape::BareResource,
    outputClass: AbstractResource::class,
    resource: ['Resource'],
    instance: true,
    type: false,
    system: false,
)]
final class ResourceFilterOperation
{
}
