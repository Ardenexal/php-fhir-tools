<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Operation\ResourceConvert;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperation;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\OperationOutputShape;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\AbstractResource;

#[FhirOperation(
    code: 'convert',
    url: 'http://hl7.org/fhir/OperationDefinition/Resource-convert',
    version: 'R4',
    inputClass: ResourceConvertInput::class,
    outputShape: OperationOutputShape::NamedBareResource,
    outputClass: AbstractResource::class,
    resource: ['Resource'],
    instance: false,
    type: false,
    system: true,
    outputParameterName: 'output',
)]
final class ResourceConvertOperation
{
}
