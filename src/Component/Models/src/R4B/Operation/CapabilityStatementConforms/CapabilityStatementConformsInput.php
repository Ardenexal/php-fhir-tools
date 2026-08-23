<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Operation\CapabilityStatementConforms;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperationParameter;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperationPayload;

#[FhirOperationPayload(
    operationUrl: 'http://hl7.org/fhir/OperationDefinition/CapabilityStatement-conforms',
    use: 'in',
    version: 'R4B',
    operation: 'CapabilityStatementConforms',
    path: '',
)]
final class CapabilityStatementConformsInput
{
    public function __construct(
        #[FhirOperationParameter(
            name: 'left',
            phpName: 'left',
            use: 'in',
            min: 0,
            max: '1',
            type: 'canonical',
            documentation: 'A canonical reference to the left-hand system\'s capability statement',
        )]
        public readonly ?string $left = null,
        #[FhirOperationParameter(
            name: 'right',
            phpName: 'right',
            use: 'in',
            min: 0,
            max: '1',
            type: 'canonical',
            documentation: 'A canonical reference to the right-hand system\'s capability statement',
        )]
        public readonly ?string $right = null,
        #[FhirOperationParameter(
            name: 'mode',
            phpName: 'mode',
            use: 'in',
            min: 0,
            max: '1',
            type: 'code',
            documentation: 'What kind of comparison to perform - server to server, or client to server (use the codes \'server/server\' or \'client/server\')',
        )]
        public readonly ?string $mode = null,
    ) {
    }
}
