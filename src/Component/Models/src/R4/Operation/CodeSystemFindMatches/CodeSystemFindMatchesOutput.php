<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Operation\CodeSystemFindMatches;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperationParameter;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperationPayload;

#[FhirOperationPayload(
    operationUrl: 'http://hl7.org/fhir/OperationDefinition/CodeSystem-find-matches',
    use: 'out',
    version: 'R4',
    operation: 'CodeSystemFindMatches',
    path: '',
)]
final class CodeSystemFindMatchesOutput
{
    /**
     * @param list<CodeSystemFindMatchesOutMatch> $match
     */
    public function __construct(
        #[FhirOperationParameter(
            name: 'match',
            phpName: 'match',
            use: 'out',
            min: 0,
            max: '*',
            partClass: CodeSystemFindMatchesOutMatch::class,
            documentation: 'Concepts returned by the server as a result of the inferencing operation',
        )]
        public readonly array $match = [],
    ) {
    }
}
