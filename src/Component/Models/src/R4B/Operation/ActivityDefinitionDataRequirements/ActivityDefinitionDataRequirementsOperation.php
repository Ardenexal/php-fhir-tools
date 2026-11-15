<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Operation\ActivityDefinitionDataRequirements;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperation;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\OperationOutputShape;
use Ardenexal\FHIRTools\Component\Models\R4B\Resource\LibraryResource;

#[FhirOperation(
    code: 'data-requirements',
    url: 'http://hl7.org/fhir/OperationDefinition/ActivityDefinition-data-requirements',
    version: 'R4B',
    inputClass: '',
    outputShape: OperationOutputShape::BareResource,
    outputClass: LibraryResource::class,
    resource: ['ActivityDefinition'],
    instance: true,
    type: false,
    system: false,
)]
final class ActivityDefinitionDataRequirementsOperation
{
}
