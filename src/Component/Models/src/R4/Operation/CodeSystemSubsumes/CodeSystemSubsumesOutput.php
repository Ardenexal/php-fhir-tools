<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Operation\CodeSystemSubsumes;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperationParameter;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperationPayload;

#[FhirOperationPayload(
    operationUrl: 'http://hl7.org/fhir/OperationDefinition/CodeSystem-subsumes',
    use: 'out',
    version: 'R4',
    operation: 'CodeSystemSubsumes',
    path: '',
)]
final class CodeSystemSubsumesOutput
{
    public function __construct(
        #[FhirOperationParameter(
            name: 'outcome',
            phpName: 'outcome',
            use: 'out',
            min: 1,
            max: '1',
            type: 'code',
            documentation: 'The subsumption relationship between code/Coding "A" and code/Coding "B". There are 4 possible codes to be returned (equivalent, subsumes, subsumed-by, and not-subsumed) as defined in the concept-subsumption-outcome value set.  If the server is unable to determine the relationship between the codes/Codings, then it returns an error response with an OperationOutcome.',
        )]
        public readonly ?string $outcome = null,
    ) {
    }
}
