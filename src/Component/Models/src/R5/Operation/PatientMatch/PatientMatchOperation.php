<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Operation\PatientMatch;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperation;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\OperationOutputShape;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\BundleResource;

#[FhirOperation(
    code: 'match',
    url: 'http://hl7.org/fhir/OperationDefinition/Patient-match',
    version: 'R5',
    inputClass: PatientMatchInput::class,
    outputShape: OperationOutputShape::BareResource,
    outputClass: BundleResource::class,
    resource: ['Patient'],
    instance: false,
    type: true,
    system: false,
)]
final class PatientMatchOperation
{
}
