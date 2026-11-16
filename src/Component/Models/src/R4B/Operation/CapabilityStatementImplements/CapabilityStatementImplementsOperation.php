<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Operation\CapabilityStatementImplements;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperation;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\OperationOutputShape;
use Ardenexal\FHIRTools\Component\Models\R4B\Resource\OperationOutcomeResource;

#[FhirOperation(
    code: 'implements',
    url: 'http://hl7.org/fhir/OperationDefinition/CapabilityStatement-implements',
    version: 'R4B',
    inputClass: CapabilityStatementImplementsInput::class,
    outputShape: OperationOutputShape::BareResource,
    outputClass: OperationOutcomeResource::class,
    resource: ['CapabilityStatement'],
    instance: true,
    type: true,
    system: false,
)]
final class CapabilityStatementImplementsOperation
{
}
