<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Operation\MeasureSubmitData;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperation;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\OperationOutputShape;

#[FhirOperation(
    code: 'submit-data',
    url: 'http://hl7.org/fhir/OperationDefinition/Measure-submit-data',
    version: 'R5',
    inputClass: MeasureSubmitDataInput::class,
    outputShape: OperationOutputShape::NoOutput,
    outputClass: null,
    resource: ['Measure'],
    instance: true,
    type: true,
    system: false,
)]
final class MeasureSubmitDataOperation
{
}
