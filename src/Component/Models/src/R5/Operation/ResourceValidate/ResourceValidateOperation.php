<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Operation\ResourceValidate;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperation;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\OperationOutputShape;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\OperationOutcomeResource;

#[FhirOperation(
    code: 'validate',
    url: 'http://hl7.org/fhir/OperationDefinition/Resource-validate',
    version: 'R5',
    inputClass: ResourceValidateInput::class,
    outputShape: OperationOutputShape::BareResource,
    outputClass: OperationOutcomeResource::class,
    resource: ['Resource'],
    instance: true,
    type: true,
    system: false,
)]
final class ResourceValidateOperation
{
}
