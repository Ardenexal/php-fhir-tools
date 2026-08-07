<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Operation\CodeSystemLookup;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperation;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\OperationOutputShape;

#[FhirOperation(
    code: 'lookup',
    url: 'http://hl7.org/fhir/OperationDefinition/CodeSystem-lookup',
    version: 'R5',
    inputClass: CodeSystemLookupInput::class,
    outputShape: OperationOutputShape::Parameters,
    outputClass: CodeSystemLookupOutput::class,
    resource: ['CodeSystem'],
    instance: true,
    type: true,
    system: false,
)]
final class CodeSystemLookupOperation
{
}
