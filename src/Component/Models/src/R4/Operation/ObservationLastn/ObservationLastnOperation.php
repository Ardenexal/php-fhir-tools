<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Operation\ObservationLastn;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperation;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\OperationOutputShape;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\BundleResource;

#[FhirOperation(
    code: 'lastn',
    url: 'http://hl7.org/fhir/OperationDefinition/Observation-lastn',
    version: 'R4',
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
