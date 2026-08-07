<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Operation\MeasureCareGaps;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperation;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\OperationOutputShape;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\BundleResource;

#[FhirOperation(
    code: 'care-gaps',
    url: 'http://hl7.org/fhir/OperationDefinition/Measure-care-gaps',
    version: 'R4',
    inputClass: MeasureCareGapsInput::class,
    outputShape: OperationOutputShape::BareResource,
    outputClass: BundleResource::class,
    resource: ['Measure'],
    instance: false,
    type: true,
    system: false,
)]
final class MeasureCareGapsOperation
{
}
