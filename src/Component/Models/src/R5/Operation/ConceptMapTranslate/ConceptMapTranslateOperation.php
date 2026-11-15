<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Operation\ConceptMapTranslate;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperation;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\OperationOutputShape;

#[FhirOperation(
    code: 'translate',
    url: 'http://hl7.org/fhir/OperationDefinition/ConceptMap-translate',
    version: 'R5',
    inputClass: ConceptMapTranslateInput::class,
    outputShape: OperationOutputShape::Parameters,
    outputClass: ConceptMapTranslateOutput::class,
    resource: ['ConceptMap'],
    instance: true,
    type: true,
    system: false,
)]
final class ConceptMapTranslateOperation
{
}
