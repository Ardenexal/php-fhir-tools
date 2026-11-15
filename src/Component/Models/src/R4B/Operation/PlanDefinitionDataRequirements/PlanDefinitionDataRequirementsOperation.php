<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Operation\PlanDefinitionDataRequirements;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperation;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\OperationOutputShape;
use Ardenexal\FHIRTools\Component\Models\R4B\Resource\LibraryResource;

#[FhirOperation(
    code: 'data-requirements',
    url: 'http://hl7.org/fhir/OperationDefinition/PlanDefinition-data-requirements',
    version: 'R4B',
    inputClass: '',
    outputShape: OperationOutputShape::BareResource,
    outputClass: LibraryResource::class,
    resource: ['PlanDefinition'],
    instance: true,
    type: false,
    system: false,
)]
final class PlanDefinitionDataRequirementsOperation
{
}
