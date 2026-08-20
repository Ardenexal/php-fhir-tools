<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Operation\ResourceConvert;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperation;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\OperationOutputShape;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\AbstractResource;

#[FhirOperation(
    code: 'convert',
    url: 'http://hl7.org/fhir/OperationDefinition/Resource-convert',
    version: 'R5',
    inputClass: ResourceConvertInput::class,
    outputShape: OperationOutputShape::BareResource,
    outputClass: AbstractResource::class,
    resource: ['Resource'],
    instance: false,
    type: false,
    system: true,
)]
final class ResourceConvertOperation
{
}
