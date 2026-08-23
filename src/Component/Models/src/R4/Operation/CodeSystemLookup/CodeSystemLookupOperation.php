<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Operation\CodeSystemLookup;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperation;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\OperationOutputShape;

#[FhirOperation(
    code: 'lookup',
    url: 'http://hl7.org/fhir/OperationDefinition/CodeSystem-lookup',
    version: 'R4',
    inputClass: CodeSystemLookupInput::class,
    outputShape: OperationOutputShape::Parameters,
    outputClass: CodeSystemLookupOutput::class,
    resource: ['CodeSystem'],
    instance: false,
    type: true,
    system: false,
)]
final class CodeSystemLookupOperation
{
}
