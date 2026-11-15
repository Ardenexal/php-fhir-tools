<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Operation\PatientMatch;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperationParameter;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperationPayload;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\AbstractResource;

#[FhirOperationPayload(
    operationUrl: 'http://hl7.org/fhir/OperationDefinition/Patient-match',
    use: 'in',
    version: 'R5',
    operation: 'PatientMatch',
    path: '',
)]
final class PatientMatchInput
{
    public function __construct(
        #[FhirOperationParameter(
            name: 'resource',
            phpName: 'resource',
            use: 'in',
            min: 1,
            max: '1',
            type: 'Resource',
            documentation: 'Use this to provide an entire set of patient details for the MPI to match against (e.g. POST a patient record to Patient/$match).',
        )]
        public readonly ?AbstractResource $resource = null,
        #[FhirOperationParameter(
            name: 'onlyCertainMatches',
            phpName: 'onlyCertainMatches',
            use: 'in',
            min: 0,
            max: '1',
            type: 'boolean',
            documentation: 'If there are multiple potential matches, then the match should not return the results with this flag set to true.  When false, the server may return multiple results with each result graded accordingly.',
        )]
        public readonly ?bool $onlyCertainMatches = null,
        #[FhirOperationParameter(
            name: 'count',
            phpName: 'count',
            use: 'in',
            min: 0,
            max: '1',
            type: 'integer',
            documentation: 'The maximum number of records to return. If no value is provided, the server decides how many matches to return. Note that clients should be careful when using this, as it may prevent probable - and valid - matches from being returned',
        )]
        public readonly ?int $count = null,
    ) {
    }
}
