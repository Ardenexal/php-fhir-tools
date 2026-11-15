<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Operation\ResourceGraph;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperationParameter;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperationPayload;

#[FhirOperationPayload(
    operationUrl: 'http://hl7.org/fhir/OperationDefinition/Resource-graph',
    use: 'in',
    version: 'R4B',
    operation: 'ResourceGraph',
    path: '',
)]
final class ResourceGraphInput
{
    public function __construct(
        #[FhirOperationParameter(
            name: 'graph',
            phpName: 'graph',
            use: 'in',
            min: 1,
            max: '1',
            type: 'uri',
            documentation: 'Servers MAY choose to allow any graph definition to be specified, but MAY require that the client choose a graph definition from a specific list of known supported definitions. The server is not required to support a formal definition of the graph on the end point',
        )]
        public readonly ?string $graph = null,
    ) {
    }
}
