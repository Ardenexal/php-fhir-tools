<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Operation\LibraryDataRequirements;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperation;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\OperationOutputShape;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\LibraryResource;

#[FhirOperation(
    code: 'data-requirements',
    url: 'http://hl7.org/fhir/OperationDefinition/Library-data-requirements',
    version: 'R4',
    inputClass: LibraryDataRequirementsInput::class,
    outputShape: OperationOutputShape::BareResource,
    outputClass: LibraryResource::class,
    resource: ['Library'],
    instance: true,
    type: false,
    system: true,
)]
final class LibraryDataRequirementsOperation
{
}
