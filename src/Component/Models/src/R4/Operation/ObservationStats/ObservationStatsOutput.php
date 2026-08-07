<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Operation\ObservationStats;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperationParameter;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperationPayload;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\ObservationResource;

#[FhirOperationPayload(
    operationUrl: 'http://hl7.org/fhir/OperationDefinition/Observation-stats',
    use: 'out',
    version: 'R4',
    operation: 'ObservationStats',
    path: '',
)]
final class ObservationStatsOutput
{
    /**
     * @param list<ObservationResource> $statistics
     * @param list<ObservationResource> $source
     */
    public function __construct(
        #[FhirOperationParameter(
            name: 'statistics',
            phpName: 'statistics',
            use: 'out',
            min: 1,
            max: '*',
            type: 'Observation',
            documentation: 'A set of observations, one observation for each code, each containing one component for each statistic. The Observation.component.code contains the statistic, and is relative to the Observation.code and cannot be interpreted independently.  The Observation will also contain a subject, effectivePeriod, and code reflecting the input parameters.  The status is fixed to `final`.',
        )]
        public readonly array $statistics = [],
        #[FhirOperationParameter(
            name: 'source',
            phpName: 'source',
            use: 'out',
            min: 0,
            max: '*',
            type: 'Observation',
            documentation: 'Source observations on which the statistics are based',
        )]
        public readonly array $source = [],
    ) {
    }
}
