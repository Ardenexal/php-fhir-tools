<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Operation\ConceptMapTranslate;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperationParameter;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperationPayload;

#[FhirOperationPayload(
    operationUrl: 'http://hl7.org/fhir/OperationDefinition/ConceptMap-translate',
    use: 'out',
    version: 'R4B',
    operation: 'ConceptMapTranslate',
    path: '',
)]
final class ConceptMapTranslateOutput
{
    /**
     * @param list<ConceptMapTranslateOutMatch> $match
     */
    public function __construct(
        #[FhirOperationParameter(
            name: 'result',
            phpName: 'result',
            use: 'out',
            min: 1,
            max: '1',
            type: 'boolean',
            documentation: 'True if the concept could be translated successfully. The value can only be true if at least one returned match has an equivalence which is not  unmatched or disjoint',
        )]
        public readonly ?bool $result = null,
        #[FhirOperationParameter(
            name: 'message',
            phpName: 'message',
            use: 'out',
            min: 0,
            max: '1',
            type: 'string',
            documentation: 'Error details, for display to a human. If this is provided when result = true, the message carries hints and warnings (e.g. a note that the matches could be improved by providing additional detail)',
        )]
        public readonly ?string $message = null,
        #[FhirOperationParameter(
            name: 'match',
            phpName: 'match',
            use: 'out',
            min: 0,
            max: '*',
            partClass: ConceptMapTranslateOutMatch::class,
            documentation: 'A concept in the target value set with an equivalence. Note that there may be multiple matches of equal or differing equivalence, and the matches may include equivalence values that mean that there is no match',
        )]
        public readonly array $match = [],
    ) {
    }
}
