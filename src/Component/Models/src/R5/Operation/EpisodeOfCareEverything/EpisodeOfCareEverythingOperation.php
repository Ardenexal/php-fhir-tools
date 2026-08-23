<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Operation\EpisodeOfCareEverything;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperation;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\OperationOutputShape;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\BundleResource;

#[FhirOperation(
    code: 'everything',
    url: 'http://hl7.org/fhir/OperationDefinition/EpisodeOfCare-everything',
    version: 'R5',
    inputClass: EpisodeOfCareEverythingInput::class,
    outputShape: OperationOutputShape::BareResource,
    outputClass: BundleResource::class,
    resource: ['EpisodeOfCare'],
    instance: true,
    type: false,
    system: false,
)]
final class EpisodeOfCareEverythingOperation
{
}
