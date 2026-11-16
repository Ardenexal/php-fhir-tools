<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Operation\ConceptMapClosure;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperationParameter;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperationPayload;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Coding;

#[FhirOperationPayload(
    operationUrl: 'http://hl7.org/fhir/OperationDefinition/ConceptMap-closure',
    use: 'in',
    version: 'R5',
    operation: 'ConceptMapClosure',
    path: '',
)]
final class ConceptMapClosureInput
{
    /**
     * @param list<Coding> $concept
     */
    public function __construct(
        #[FhirOperationParameter(
            name: 'name',
            phpName: 'name',
            use: 'in',
            min: 1,
            max: '1',
            type: 'string',
            documentation: 'The name that defines the particular context for the subsumption based closure table',
        )]
        public readonly ?string $name = null,
        #[FhirOperationParameter(
            name: 'concept',
            phpName: 'concept',
            use: 'in',
            min: 0,
            max: '*',
            type: 'Coding',
            documentation: 'Concepts to add to the closure table',
        )]
        public readonly array $concept = [],
        #[FhirOperationParameter(
            name: 'version',
            phpName: 'version',
            use: 'in',
            min: 0,
            max: '1',
            type: 'string',
            documentation: 'A request to resynchronise - request to send all new entries since the nominated version was sent by the server',
        )]
        public readonly ?string $version = null,
    ) {
    }
}
