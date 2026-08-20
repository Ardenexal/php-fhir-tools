<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Operation\ResourceGraphql;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperation;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\OperationOutputShape;
use Ardenexal\FHIRTools\Component\Models\R4B\Resource\BinaryResource;

#[FhirOperation(
    code: 'graphql',
    url: 'http://hl7.org/fhir/OperationDefinition/Resource-graphql',
    version: 'R4B',
    inputClass: ResourceGraphqlInput::class,
    outputShape: OperationOutputShape::NamedBareResource,
    outputClass: BinaryResource::class,
    resource: ['Resource'],
    instance: true,
    type: false,
    system: true,
    outputParameterName: 'result',
)]
final class ResourceGraphqlOperation
{
}
