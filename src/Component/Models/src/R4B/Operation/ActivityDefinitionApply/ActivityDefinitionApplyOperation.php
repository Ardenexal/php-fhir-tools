<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Operation\ActivityDefinitionApply;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperation;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\OperationOutputShape;
use Ardenexal\FHIRTools\Component\Models\R4B\Resource\AbstractResource;

#[FhirOperation(
    code: 'apply',
    url: 'http://hl7.org/fhir/OperationDefinition/ActivityDefinition-apply',
    version: 'R4B',
    inputClass: ActivityDefinitionApplyInput::class,
    outputShape: OperationOutputShape::BareResource,
    outputClass: AbstractResource::class,
    resource: ['ActivityDefinition'],
    instance: true,
    type: true,
    system: false,
)]
final class ActivityDefinitionApplyOperation
{
}
