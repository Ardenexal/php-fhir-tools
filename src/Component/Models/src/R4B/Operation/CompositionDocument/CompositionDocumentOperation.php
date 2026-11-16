<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Operation\CompositionDocument;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperation;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\OperationOutputShape;

#[FhirOperation(
    code: 'document',
    url: 'http://hl7.org/fhir/OperationDefinition/Composition-document',
    version: 'R4B',
    inputClass: CompositionDocumentInput::class,
    outputShape: OperationOutputShape::NoOutput,
    outputClass: null,
    resource: ['Composition'],
    instance: true,
    type: true,
    system: false,
)]
final class CompositionDocumentOperation
{
}
