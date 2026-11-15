<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Operation\ObservationStats;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperation;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\OperationOutputShape;

#[FhirOperation(
    code: 'stats',
    url: 'http://hl7.org/fhir/OperationDefinition/Observation-stats',
    version: 'R4B',
    inputClass: ObservationStatsInput::class,
    outputShape: OperationOutputShape::Parameters,
    outputClass: ObservationStatsOutput::class,
    resource: ['Observation'],
    instance: false,
    type: true,
    system: false,
)]
final class ObservationStatsOperation
{
}
