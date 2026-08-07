<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Operation\CodeSystemSubsumes;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperationParameter;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperationPayload;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Coding;

#[FhirOperationPayload(
    operationUrl: 'http://hl7.org/fhir/OperationDefinition/CodeSystem-subsumes',
    use: 'in',
    version: 'R4B',
    operation: 'CodeSystemSubsumes',
    path: '',
)]
final class CodeSystemSubsumesInput
{
    public function __construct(
        #[FhirOperationParameter(
            name: 'codeA',
            phpName: 'codeA',
            use: 'in',
            min: 0,
            max: '1',
            type: 'code',
            documentation: 'The "A" code that is to be tested. If a code is provided, a system must be provided',
        )]
        public readonly ?string $codeA = null,
        #[FhirOperationParameter(
            name: 'codeB',
            phpName: 'codeB',
            use: 'in',
            min: 0,
            max: '1',
            type: 'code',
            documentation: 'The "B" code that is to be tested. If a code is provided, a system must be provided',
        )]
        public readonly ?string $codeB = null,
        #[FhirOperationParameter(
            name: 'system',
            phpName: 'system',
            use: 'in',
            min: 0,
            max: '1',
            type: 'uri',
            documentation: 'The code system in which subsumption testing is to be performed. This must be provided unless the operation is invoked on a code system instance',
        )]
        public readonly ?string $system = null,
        #[FhirOperationParameter(
            name: 'version',
            phpName: 'version',
            use: 'in',
            min: 0,
            max: '1',
            type: 'string',
            documentation: 'The version of the code system, if one was provided in the source data',
        )]
        public readonly ?string $version = null,
        #[FhirOperationParameter(
            name: 'codingA',
            phpName: 'codingA',
            use: 'in',
            min: 0,
            max: '1',
            type: 'Coding',
            documentation: 'The "A" Coding that is to be tested. The code system does not have to match the specified subsumption code system, but the relationships between the code systems must be well established',
        )]
        public readonly ?Coding $codingA = null,
        #[FhirOperationParameter(
            name: 'codingB',
            phpName: 'codingB',
            use: 'in',
            min: 0,
            max: '1',
            type: 'Coding',
            documentation: 'The "B" Coding that is to be tested. The code system does not have to match the specified subsumption code system, but the relationships between the code systems must be well established',
        )]
        public readonly ?Coding $codingB = null,
    ) {
    }
}
