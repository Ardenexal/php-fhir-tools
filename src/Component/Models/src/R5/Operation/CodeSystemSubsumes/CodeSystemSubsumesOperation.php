<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Operation\CodeSystemSubsumes;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperation;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\OperationOutputShape;

#[FhirOperation(
    code: 'subsumes',
    url: 'http://hl7.org/fhir/OperationDefinition/CodeSystem-subsumes',
    version: 'R5',
    inputClass: CodeSystemSubsumesInput::class,
    outputShape: OperationOutputShape::Parameters,
    outputClass: CodeSystemSubsumesOutput::class,
    resource: ['CodeSystem'],
    instance: true,
    type: true,
    system: false,
)]
final class CodeSystemSubsumesOperation
{
}
