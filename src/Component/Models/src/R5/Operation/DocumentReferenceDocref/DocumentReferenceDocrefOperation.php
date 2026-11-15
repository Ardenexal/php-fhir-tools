<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Operation\DocumentReferenceDocref;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperation;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\OperationOutputShape;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\BundleResource;

#[FhirOperation(
    code: 'docref',
    url: 'http://hl7.org/fhir/OperationDefinition/DocumentReference-docref',
    version: 'R5',
    inputClass: DocumentReferenceDocrefInput::class,
    outputShape: OperationOutputShape::BareResource,
    outputClass: BundleResource::class,
    resource: ['DocumentReference'],
    instance: false,
    type: true,
    system: false,
)]
final class DocumentReferenceDocrefOperation
{
}
