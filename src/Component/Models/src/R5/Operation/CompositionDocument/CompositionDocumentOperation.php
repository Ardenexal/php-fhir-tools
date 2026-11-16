<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Operation\CompositionDocument;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperation;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\OperationOutputShape;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\BundleResource;

#[FhirOperation(
    code: 'document',
    url: 'http://hl7.org/fhir/OperationDefinition/Composition-document',
    version: 'R5',
    inputClass: CompositionDocumentInput::class,
    outputShape: OperationOutputShape::BareResource,
    outputClass: BundleResource::class,
    resource: ['Composition'],
    instance: true,
    type: false,
    system: false,
)]
final class CompositionDocumentOperation
{
}
