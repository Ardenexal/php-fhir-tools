<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Operation\SpecimenDefinitionApply;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperation;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\OperationOutputShape;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\SpecimenResource;

#[FhirOperation(
    code: 'apply',
    url: 'http://hl7.org/fhir/OperationDefinition/SpecimenDefinition-apply',
    version: 'R5',
    inputClass: SpecimenDefinitionApplyInput::class,
    outputShape: OperationOutputShape::BareResource,
    outputClass: SpecimenResource::class,
    resource: ['SpecimenDefinition'],
    instance: true,
    type: true,
    system: false,
)]
final class SpecimenDefinitionApplyOperation
{
}
