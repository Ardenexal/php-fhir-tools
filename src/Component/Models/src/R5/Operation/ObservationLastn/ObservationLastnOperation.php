<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Operation\ObservationLastn;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperation;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\OperationOutputShape;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\BundleResource;

#[FhirOperation(
    code: 'lastn',
    url: 'http://hl7.org/fhir/OperationDefinition/Observation-lastn',
    version: 'R5',
    inputClass: ObservationLastnInput::class,
    outputShape: OperationOutputShape::BareResource,
    outputClass: BundleResource::class,
    resource: ['Observation'],
    instance: false,
    type: true,
    system: false,
)]
final class ObservationLastnOperation
{
}
