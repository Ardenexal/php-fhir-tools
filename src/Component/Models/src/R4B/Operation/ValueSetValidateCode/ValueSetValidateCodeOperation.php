<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Operation\ValueSetValidateCode;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperation;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\OperationOutputShape;

#[FhirOperation(
    code: 'validate-code',
    url: 'http://hl7.org/fhir/OperationDefinition/ValueSet-validate-code',
    version: 'R4B',
    inputClass: ValueSetValidateCodeInput::class,
    outputShape: OperationOutputShape::Parameters,
    outputClass: ValueSetValidateCodeOutput::class,
    resource: ['ValueSet'],
    instance: true,
    type: true,
    system: false,
)]
final class ValueSetValidateCodeOperation
{
}
