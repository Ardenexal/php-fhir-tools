<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Operation\ConceptMapClosure;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperation;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\OperationOutputShape;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\ConceptMapResource;

#[FhirOperation(
    code: 'closure',
    url: 'http://hl7.org/fhir/OperationDefinition/ConceptMap-closure',
    version: 'R5',
    inputClass: ConceptMapClosureInput::class,
    outputShape: OperationOutputShape::BareResource,
    outputClass: ConceptMapResource::class,
    resource: ['ConceptMap'],
    instance: false,
    type: false,
    system: true,
)]
final class ConceptMapClosureOperation
{
}
