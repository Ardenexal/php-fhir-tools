<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Operation\EncounterEverything;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperationParameter;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperationPayload;

#[FhirOperationPayload(
    operationUrl: 'http://hl7.org/fhir/OperationDefinition/Encounter-everything',
    use: 'in',
    version: 'R4',
    operation: 'EncounterEverything',
    path: '',
)]
final class EncounterEverythingInput
{
    /**
     * @param list<string> $type
     */
    public function __construct(
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
            documentation: 'One or more parameters, each containing one or more comma-delimited FHIR resource types to include in the return resources. In the absense of any specified types, the server returns all resource types',
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
