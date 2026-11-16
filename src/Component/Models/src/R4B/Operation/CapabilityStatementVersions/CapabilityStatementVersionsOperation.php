<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Operation\CapabilityStatementVersions;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperation;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\OperationOutputShape;

#[FhirOperation(
    code: 'versions',
    url: 'http://hl7.org/fhir/OperationDefinition/CapabilityStatement-versions',
    version: 'R4B',
    inputClass: '',
    outputShape: OperationOutputShape::Parameters,
    outputClass: CapabilityStatementVersionsOutput::class,
    resource: ['CapabilityStatement'],
    instance: false,
    type: false,
    system: true,
)]
final class CapabilityStatementVersionsOperation
{
}
