<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Operation\DocumentReferenceGenerate;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperation;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\OperationOutputShape;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\BundleResource;

#[FhirOperation(
    code: 'generate',
    url: 'http://hl7.org/fhir/OperationDefinition/DocumentReference-generate',
    version: 'R5',
    inputClass: DocumentReferenceGenerateInput::class,
    outputShape: OperationOutputShape::BareResource,
    outputClass: BundleResource::class,
    resource: ['DocumentReference'],
    instance: false,
    type: true,
    system: false,
)]
final class DocumentReferenceGenerateOperation
{
}
