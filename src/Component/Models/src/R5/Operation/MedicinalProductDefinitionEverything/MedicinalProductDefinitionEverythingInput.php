<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Operation\MedicinalProductDefinitionEverything;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperationParameter;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperationPayload;

#[FhirOperationPayload(
    operationUrl: 'http://hl7.org/fhir/OperationDefinition/MedicinalProductDefinition-everything',
    use: 'in',
    version: 'R5',
    operation: 'MedicinalProductDefinitionEverything',
    path: '',
)]
final class MedicinalProductDefinitionEverythingInput
{
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
