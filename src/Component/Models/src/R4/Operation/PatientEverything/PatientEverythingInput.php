<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Operation\PatientEverything;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperationParameter;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperationPayload;

#[FhirOperationPayload(
    operationUrl: 'http://hl7.org/fhir/OperationDefinition/Patient-everything',
    use: 'in',
    version: 'R4',
    operation: 'PatientEverything',
    path: '',
)]
final class PatientEverythingInput
{
    /**
     * @param list<string> $type
     */
    public function __construct(
        #[FhirOperationParameter(
            name: 'start',
            phpName: 'start',
            use: 'in',
            min: 0,
            max: '1',
            type: 'date',
            documentation: 'The date range relates to care dates, not record currency dates - e.g. all records relating to care provided in a certain date range. If no start date is provided, all records prior to the end date are in scope.',
        )]
        public readonly ?string $start = null,
        #[FhirOperationParameter(
            name: 'end',
            phpName: 'end',
            use: 'in',
            min: 0,
            max: '1',
            type: 'date',
            documentation: 'The date range relates to care dates, not record currency dates - e.g. all records relating to care provided in a certain date range. If no end date is provided, all records subsequent to the start date are in scope.',
        )]
        public readonly ?string $end = null,
        #[FhirOperationParameter(
            name: '_since',
            phpName: 'since',
            use: 'in',
            min: 0,
            max: '1',
            type: 'instant',
            documentation: 'Resources updated after this period will be included in the response. The intent of this parameter is to allow a client to request only records that have changed since the last request, based on either the return header time, or or (for asynchronous use), the transaction time',
        )]
        public readonly ?string $since = null,
        #[FhirOperationParameter(
            name: '_type',
            phpName: 'type',
            use: 'in',
            min: 0,
            max: '*',
            type: 'code',
            documentation: 'One or more parameters, each containing one or more comma-delimited FHIR resource types to include in the return resources. In the absence of any specified types, the server returns all resource types',
        )]
        public readonly array $type = [],
        #[FhirOperationParameter(
            name: '_count',
            phpName: 'count',
            use: 'in',
            min: 0,
            max: '1',
            type: 'integer',
            documentation: 'See discussion below on the utility of paging through the results of the $everything operation',
        )]
        public readonly ?int $count = null,
    ) {
    }
}
