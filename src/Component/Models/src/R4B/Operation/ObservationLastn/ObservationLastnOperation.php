<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Operation\ObservationLastn;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperation;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\OperationOutputShape;
use Ardenexal\FHIRTools\Component\Models\R4B\Resource\BundleResource;

#[FhirOperation(
    code: 'lastn',
    url: 'http://hl7.org/fhir/OperationDefinition/Observation-lastn',
    version: 'R4B',
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
