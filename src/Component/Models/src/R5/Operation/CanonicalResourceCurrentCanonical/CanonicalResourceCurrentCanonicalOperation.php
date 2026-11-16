<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Operation\CanonicalResourceCurrentCanonical;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperation;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\OperationOutputShape;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\AbstractResource;

#[FhirOperation(
    code: 'current-canonical',
    url: 'http://hl7.org/fhir/OperationDefinition/CanonicalResource-current-canonical',
    version: 'R5',
    inputClass: CanonicalResourceCurrentCanonicalInput::class,
    outputShape: OperationOutputShape::NamedBareResource,
    outputClass: AbstractResource::class,
    resource: ['CanonicalResource'],
    instance: false,
    type: true,
    system: true,
    outputParameterName: 'result',
)]
final class CanonicalResourceCurrentCanonicalOperation
{
}
