<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Operation\MeasureCareGaps;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperationParameter;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperationPayload;

#[FhirOperationPayload(
    operationUrl: 'http://hl7.org/fhir/OperationDefinition/Measure-care-gaps',
    use: 'in',
    version: 'R4B',
    operation: 'MeasureCareGaps',
    path: '',
)]
final class MeasureCareGapsInput
{
    public function __construct(
        #[FhirOperationParameter(
            name: 'periodStart',
            phpName: 'periodStart',
            use: 'in',
            min: 1,
            max: '1',
            type: 'date',
            documentation: 'The start of the measurement period. In keeping with the semantics of the date parameter used in the FHIR search operation, the period will start at the beginning of the period implied by the supplied timestamp. E.g. a value of 2014 would set the period s',
        )]
        public readonly ?string $periodStart = null,
        #[FhirOperationParameter(
            name: 'periodEnd',
            phpName: 'periodEnd',
            use: 'in',
            min: 1,
            max: '1',
            type: 'date',
            documentation: 'The end of the measurement period. The period will end at the end of the period implied by the supplied timestamp. E.g. a value of 2014 would set the period end to be 2014-12-31T23:59:59 inclusive',
        )]
        public readonly ?string $periodEnd = null,
        #[FhirOperationParameter(
            name: 'topic',
            phpName: 'topic',
            use: 'in',
            min: 1,
            max: '1',
            type: 'string',
            documentation: 'The topic to be used to determine which measures are considered for the care gaps report. Any measure with the given topic will be included in the report',
        )]
        public readonly ?string $topic = null,
        #[FhirOperationParameter(
            name: 'subject',
            phpName: 'subject',
            use: 'in',
            min: 1,
            max: '1',
            type: 'string',
            searchType: 'reference',
            documentation: 'Subject for which the care gaps report will be produced',
        )]
        public readonly ?string $subject = null,
    ) {
    }
}
