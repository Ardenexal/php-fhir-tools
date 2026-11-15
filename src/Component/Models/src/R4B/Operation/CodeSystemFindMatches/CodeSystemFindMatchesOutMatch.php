<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Operation\CodeSystemFindMatches;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperationParameter;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperationPayload;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Coding;

#[FhirOperationPayload(
    operationUrl: 'http://hl7.org/fhir/OperationDefinition/CodeSystem-find-matches',
    use: 'out',
    version: 'R4B',
    operation: 'CodeSystemFindMatches',
    path: 'match',
)]
final class CodeSystemFindMatchesOutMatch
{
    /**
     * @param list<CodeSystemFindMatchesOutMatchUnmatched> $unmatched
     */
    public function __construct(
        #[FhirOperationParameter(
            name: 'code',
            phpName: 'code',
            use: 'out',
            min: 1,
            max: '1',
            type: 'Coding',
            documentation: 'A code that matches the properties provided',
        )]
        public readonly ?Coding $code = null,
        #[FhirOperationParameter(
            name: 'unmatched',
            phpName: 'unmatched',
            use: 'out',
            min: 0,
            max: '*',
            partClass: CodeSystemFindMatchesOutMatchUnmatched::class,
            documentation: 'One or more properties that contain properties that could not be matched into the code',
        )]
        public readonly array $unmatched = [],
        #[FhirOperationParameter(
            name: 'comment',
            phpName: 'comment',
            use: 'out',
            min: 0,
            max: '1',
            type: 'string',
            documentation: 'Information about the quality of the match, if operation is for a human',
        )]
        public readonly ?string $comment = null,
    ) {
    }
}
