<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Operation\CodeSystemValidateCode;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperation;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\OperationOutputShape;

#[FhirOperation(
    code: 'validate-code',
    url: 'http://hl7.org/fhir/OperationDefinition/CodeSystem-validate-code',
    version: 'R5',
    inputClass: CodeSystemValidateCodeInput::class,
    outputShape: OperationOutputShape::Parameters,
    outputClass: CodeSystemValidateCodeOutput::class,
    resource: ['CodeSystem'],
    instance: true,
    type: true,
    system: false,
)]
final class CodeSystemValidateCodeOperation
{
}
