<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Operation\CapabilityStatementVersions;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperationParameter;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperationPayload;

#[FhirOperationPayload(
    operationUrl: 'http://hl7.org/fhir/OperationDefinition/CapabilityStatement-versions',
    use: 'out',
    version: 'R4',
    operation: 'CapabilityStatementVersions',
    path: '',
)]
final class CapabilityStatementVersionsOutput
{
    /**
     * @param list<string> $version
     */
    public function __construct(
        #[FhirOperationParameter(
            name: 'version',
            phpName: 'version',
            use: 'out',
            min: 1,
            max: '*',
            type: 'code',
            documentation: 'A version supported by the server. Use the major.minor version like 3.0',
        )]
        public readonly array $version = [],
        #[FhirOperationParameter(
            name: 'default',
            phpName: 'default',
            use: 'out',
            min: 1,
            max: '1',
            type: 'code',
            documentation: 'The default version for the server. Use the major.minor version like 3.0',
        )]
        public readonly ?string $default = null,
    ) {
    }
}
