<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Operation\CodeSystemValidateCode;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperationParameter;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperationPayload;

#[FhirOperationPayload(
    operationUrl: 'http://hl7.org/fhir/OperationDefinition/CodeSystem-validate-code',
    use: 'out',
    version: 'R4B',
    operation: 'CodeSystemValidateCode',
    path: '',
)]
final class CodeSystemValidateCodeOutput
{
    public function __construct(
        #[FhirOperationParameter(
            name: 'result',
            phpName: 'result',
            use: 'out',
            min: 1,
            max: '1',
            type: 'boolean',
            documentation: 'True if the concept details supplied are valid',
        )]
        public readonly ?bool $result = null,
        #[FhirOperationParameter(
            name: 'message',
            phpName: 'message',
            use: 'out',
            min: 0,
            max: '1',
            type: 'string',
            documentation: 'Error details, if result = false. If this is provided when result = true, the message carries hints and warnings',
        )]
        public readonly ?string $message = null,
        #[FhirOperationParameter(
            name: 'display',
            phpName: 'display',
            use: 'out',
            min: 0,
            max: '1',
            type: 'string',
            documentation: 'A valid display for the concept if the system wishes to display this to a user',
        )]
        public readonly ?string $display = null,
    ) {
    }
}
