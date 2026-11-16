<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Operation\StructureMapTransform;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperation;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\OperationOutputShape;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\AbstractResource;

#[FhirOperation(
    code: 'transform',
    url: 'http://hl7.org/fhir/OperationDefinition/StructureMap-transform',
    version: 'R4',
    inputClass: StructureMapTransformInput::class,
    outputShape: OperationOutputShape::BareResource,
    outputClass: AbstractResource::class,
    resource: ['StructureMap'],
    instance: true,
    type: true,
    system: false,
)]
final class StructureMapTransformOperation
{
}
