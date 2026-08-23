<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Operation\PatientMerge;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperation;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\OperationOutputShape;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\ParametersResource;

#[FhirOperation(
    code: 'merge',
    url: 'http://hl7.org/fhir/OperationDefinition/Patient-merge',
    version: 'R5',
    inputClass: PatientMergeInput::class,
    outputShape: OperationOutputShape::BareResource,
    outputClass: ParametersResource::class,
    resource: ['Patient'],
    instance: false,
    type: true,
    system: false,
)]
final class PatientMergeOperation
{
}
