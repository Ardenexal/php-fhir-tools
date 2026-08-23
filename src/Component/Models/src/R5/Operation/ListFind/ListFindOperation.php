<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Operation\ListFind;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperation;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\OperationOutputShape;

#[FhirOperation(
    code: 'find',
    url: 'http://hl7.org/fhir/OperationDefinition/List-find',
    version: 'R5',
    inputClass: ListFindInput::class,
    outputShape: OperationOutputShape::NoOutput,
    outputClass: null,
    resource: ['List'],
    instance: false,
    type: true,
    system: false,
)]
final class ListFindOperation
{
}
