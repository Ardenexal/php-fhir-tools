<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Operation\ConceptMapClosure;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperation;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\OperationOutputShape;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\ConceptMapResource;

#[FhirOperation(
    code: 'closure',
    url: 'http://hl7.org/fhir/OperationDefinition/ConceptMap-closure',
    version: 'R4',
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
