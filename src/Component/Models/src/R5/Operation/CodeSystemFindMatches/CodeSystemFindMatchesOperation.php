<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Operation\CodeSystemFindMatches;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperation;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\OperationOutputShape;

#[FhirOperation(
    code: 'find-matches',
    url: 'http://hl7.org/fhir/OperationDefinition/CodeSystem-find-matches',
    version: 'R5',
    inputClass: CodeSystemFindMatchesInput::class,
    outputShape: OperationOutputShape::Parameters,
    outputClass: CodeSystemFindMatchesOutput::class,
    resource: ['CodeSystem'],
    instance: true,
    type: true,
    system: false,
)]
final class CodeSystemFindMatchesOperation
{
}
