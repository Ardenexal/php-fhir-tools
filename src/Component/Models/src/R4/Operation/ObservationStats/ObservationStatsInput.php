<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Operation\ObservationStats;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperationParameter;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperationPayload;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Coding;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Period;

#[FhirOperationPayload(
    operationUrl: 'http://hl7.org/fhir/OperationDefinition/Observation-stats',
    use: 'in',
    version: 'R4',
    operation: 'ObservationStats',
    path: '',
)]
final class ObservationStatsInput
{
    /**
     * @param list<string> $code
     * @param list<Coding> $coding
     * @param list<string> $statistic
     */
    public function __construct(
        #[FhirOperationParameter(
            name: 'subject',
            phpName: 'subject',
            use: 'in',
            min: 1,
            max: '1',
            type: 'uri',
            documentation: 'The subject of the relevant Observations, which has the value of the Observation.subject.reference. E.g. \'Patient/123\'. Reference can be to an absolute URL, but servers only perform stats on their own observations',
        )]
        public readonly ?string $subject = null,
        #[FhirOperationParameter(
            name: 'code',
            phpName: 'code',
            use: 'in',
            min: 0,
            max: '*',
            type: 'string',
            documentation: "The test code(s) upon which the statistics are being performed. Provide along with a system, or as a coding. For example, the LOINC code  = \r2339-0 (Glucose [Mass/\u{200B}volume] in Blood) will evaluate all relevant Observations with this code in `Observation.code` and `Observation.component.code`. For LOINC codes that are panels, e.g., 85354-9(Blood pressure panel with all children optional), the stats operation returns statistics for each of the individual panel measurements.  That means it will include and evaluate all values grouped by code for all the individual observations that are: 1) referenced in   `.related` for `.related.type` = 'has-member'  and 2) component observations in `Observation.component`.",
        )]
        public readonly array $code = [],
        #[FhirOperationParameter(
            name: 'system',
            phpName: 'system',
            use: 'in',
            min: 0,
            max: '1',
            type: 'uri',
            documentation: 'The system for the code(s). Or provide a coding instead',
        )]
        public readonly ?string $system = null,
        #[FhirOperationParameter(
            name: 'coding',
            phpName: 'coding',
            use: 'in',
            min: 0,
            max: '*',
            type: 'Coding',
            documentation: 'The test code upon which the statistics are being performed, as a Coding',
        )]
        public readonly array $coding = [],
        #[FhirOperationParameter(
            name: 'duration',
            phpName: 'duration',
            use: 'in',
            min: 0,
            max: '1',
            type: 'decimal',
            documentation: 'The time period of interest given as hours.  For example, the duration = "1" represents the last hour - the time period from on hour ago to now',
        )]
        public readonly ?string $duration = null,
        #[FhirOperationParameter(
            name: 'period',
            phpName: 'period',
            use: 'in',
            min: 0,
            max: '1',
            type: 'Period',
            documentation: 'The time period over which the calculations to be performed, if a duration is not provided',
        )]
        public readonly ?Period $period = null,
        #[FhirOperationParameter(
            name: 'statistic',
            phpName: 'statistic',
            use: 'in',
            min: 1,
            max: '*',
            type: 'code',
            documentation: 'average|max|min|count  The statistical operations to be performed on the relevant operations. Multiple statistics operations can be specified. These codes are defined [here](valueset-observation-statistics.html)',
        )]
        public readonly array $statistic = [],
        #[FhirOperationParameter(
            name: 'include',
            phpName: 'include',
            use: 'in',
            min: 0,
            max: '1',
            type: 'boolean',
            documentation: 'Whether to return the observations on which the statistics are based',
        )]
        public readonly ?bool $include = null,
        #[FhirOperationParameter(
            name: 'limit',
            phpName: 'limit',
            use: 'in',
            min: 0,
            max: '1',
            type: 'positiveInt',
            documentation: 'If an include parameter is specified, a limit may also be specified to limit the number of source Observations returned.  If the include paramter is absent or equal to "false" the limit parameter SHALL be ignored by the server',
        )]
        public readonly ?int $limit = null,
    ) {
    }
}
