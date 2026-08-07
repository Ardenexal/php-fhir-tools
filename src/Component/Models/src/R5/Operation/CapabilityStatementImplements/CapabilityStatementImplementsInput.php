<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Operation\CapabilityStatementImplements;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperationParameter;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperationPayload;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\CapabilityStatementResource;

#[FhirOperationPayload(
    operationUrl: 'http://hl7.org/fhir/OperationDefinition/CapabilityStatement-implements',
    use: 'in',
    version: 'R5',
    operation: 'CapabilityStatementImplements',
    path: '',
)]
final class CapabilityStatementImplementsInput
{
    public function __construct(
        #[FhirOperationParameter(
            name: 'server',
            phpName: 'server',
            use: 'in',
            min: 0,
            max: '1',
            type: 'canonical',
            documentation: 'A canonical reference to the server capability statement - use this if the implements is not invoked on an instance (or on the /metadata end-point)',
        )]
        public readonly ?string $server = null,
        #[FhirOperationParameter(
            name: 'client',
            phpName: 'client',
            use: 'in',
            min: 0,
            max: '1',
            type: 'canonical',
            documentation: 'A canonical reference to the client capability statement - use this if the implements is not invoked on an instance (or on the /metadata end-point)',
        )]
        public readonly ?string $client = null,
        #[FhirOperationParameter(
            name: 'resource',
            phpName: 'resource',
            use: 'in',
            min: 0,
            max: '1',
            type: 'CapabilityStatement',
            documentation: 'The client capability statement, provided inline',
        )]
        public readonly ?CapabilityStatementResource $resource = null,
    ) {
    }
}
