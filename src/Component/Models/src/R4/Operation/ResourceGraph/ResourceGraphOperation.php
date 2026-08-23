<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Operation\ResourceGraph;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperation;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\OperationOutputShape;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\BundleResource;

#[FhirOperation(
    code: 'graph',
    url: 'http://hl7.org/fhir/OperationDefinition/Resource-graph',
    version: 'R4',
    inputClass: ResourceGraphInput::class,
    outputShape: OperationOutputShape::NamedBareResource,
    outputClass: BundleResource::class,
    resource: ['Resource'],
    instance: true,
    type: false,
    system: false,
    outputParameterName: 'result',
)]
final class ResourceGraphOperation
{
}
