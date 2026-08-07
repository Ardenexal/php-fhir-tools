<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Operation\CapabilityStatementSubset;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperation;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\OperationOutputShape;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\CapabilityStatementResource;

#[FhirOperation(
    code: 'subset',
    url: 'http://hl7.org/fhir/OperationDefinition/CapabilityStatement-subset',
    version: 'R4',
    inputClass: CapabilityStatementSubsetInput::class,
    outputShape: OperationOutputShape::BareResource,
    outputClass: CapabilityStatementResource::class,
    resource: ['CapabilityStatement'],
    instance: true,
    type: true,
    system: false,
)]
final class CapabilityStatementSubsetOperation
{
}
