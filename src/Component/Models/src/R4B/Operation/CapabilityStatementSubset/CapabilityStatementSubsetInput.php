<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Operation\CapabilityStatementSubset;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperationParameter;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperationPayload;

#[FhirOperationPayload(
    operationUrl: 'http://hl7.org/fhir/OperationDefinition/CapabilityStatement-subset',
    use: 'in',
    version: 'R4B',
    operation: 'CapabilityStatementSubset',
    path: '',
)]
final class CapabilityStatementSubsetInput
{
    /**
     * @param list<string> $resource
     */
    public function __construct(
        #[FhirOperationParameter(
            name: 'server',
            phpName: 'server',
            use: 'in',
            min: 0,
            max: '1',
            type: 'uri',
            documentation: 'The canonical URL - use this if the subset is not invoked on an instance (or on the /metadata end-point)',
        )]
        public readonly ?string $server = null,
        #[FhirOperationParameter(
            name: 'resource',
            phpName: 'resource',
            use: 'in',
            min: 1,
            max: '*',
            type: 'code',
            documentation: 'A resource that the client would like to include in the return',
        )]
        public readonly array $resource = [],
    ) {
    }
}
