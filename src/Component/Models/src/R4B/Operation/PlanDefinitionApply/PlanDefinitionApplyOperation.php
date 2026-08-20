<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Operation\PlanDefinitionApply;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperation;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\OperationOutputShape;
use Ardenexal\FHIRTools\Component\Models\R4B\Resource\CarePlanResource;

#[FhirOperation(
    code: 'apply',
    url: 'http://hl7.org/fhir/OperationDefinition/PlanDefinition-apply',
    version: 'R4B',
    inputClass: PlanDefinitionApplyInput::class,
    outputShape: OperationOutputShape::BareResource,
    outputClass: CarePlanResource::class,
    resource: ['PlanDefinition'],
    instance: true,
    type: true,
    system: false,
)]
final class PlanDefinitionApplyOperation
{
}
