<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Operation\PatientEverything;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperation;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\OperationOutputShape;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\BundleResource;

#[FhirOperation(
    code: 'everything',
    url: 'http://hl7.org/fhir/OperationDefinition/Patient-everything',
    version: 'R5',
    inputClass: PatientEverythingInput::class,
    outputShape: OperationOutputShape::BareResource,
    outputClass: BundleResource::class,
    resource: ['Patient'],
    instance: true,
    type: true,
    system: false,
)]
final class PatientEverythingOperation
{
}
