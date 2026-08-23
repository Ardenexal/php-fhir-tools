<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Operation\CapabilityStatementImplements;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperation;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\OperationOutputShape;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\OperationOutcomeResource;

#[FhirOperation(
    code: 'implements',
    url: 'http://hl7.org/fhir/OperationDefinition/CapabilityStatement-implements',
    version: 'R4',
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
