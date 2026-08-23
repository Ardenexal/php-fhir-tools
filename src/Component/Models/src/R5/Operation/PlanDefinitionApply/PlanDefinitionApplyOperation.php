<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Operation\PlanDefinitionApply;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperation;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\OperationOutputShape;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\BundleResource;

#[FhirOperation(
    code: 'apply',
    url: 'http://hl7.org/fhir/OperationDefinition/PlanDefinition-apply',
    version: 'R5',
    inputClass: PlanDefinitionApplyInput::class,
    outputShape: OperationOutputShape::BareResource,
    outputClass: BundleResource::class,
    resource: ['PlanDefinition'],
    instance: true,
    type: true,
    system: false,
)]
final class PlanDefinitionApplyOperation
{
}
