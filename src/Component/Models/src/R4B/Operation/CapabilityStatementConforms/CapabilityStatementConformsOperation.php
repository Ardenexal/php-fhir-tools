<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Operation\CapabilityStatementConforms;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperation;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\OperationOutputShape;

#[FhirOperation(
    code: 'conforms',
    url: 'http://hl7.org/fhir/OperationDefinition/CapabilityStatement-conforms',
    version: 'R4B',
    inputClass: CapabilityStatementConformsInput::class,
    outputShape: OperationOutputShape::Parameters,
    outputClass: CapabilityStatementConformsOutput::class,
    resource: ['CapabilityStatement'],
    instance: false,
    type: true,
    system: false,
)]
final class CapabilityStatementConformsOperation
{
}
