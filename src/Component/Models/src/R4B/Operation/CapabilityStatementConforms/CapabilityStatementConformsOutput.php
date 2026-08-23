<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Operation\CapabilityStatementConforms;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperationParameter;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperationPayload;
use Ardenexal\FHIRTools\Component\Models\R4B\Resource\CapabilityStatementResource;
use Ardenexal\FHIRTools\Component\Models\R4B\Resource\OperationOutcomeResource;

#[FhirOperationPayload(
    operationUrl: 'http://hl7.org/fhir/OperationDefinition/CapabilityStatement-conforms',
    use: 'out',
    version: 'R4B',
    operation: 'CapabilityStatementConforms',
    path: '',
)]
final class CapabilityStatementConformsOutput
{
    public function __construct(
        #[FhirOperationParameter(
            name: 'issues',
            phpName: 'issues',
            use: 'out',
            min: 1,
            max: '1',
            type: 'OperationOutcome',
            documentation: 'Outcome of the CapabilityStatement test',
        )]
        public readonly ?OperationOutcomeResource $issues = null,
        #[FhirOperationParameter(
            name: 'union',
            phpName: 'union',
            use: 'out',
            min: 0,
            max: '1',
            type: 'CapabilityStatement',
            documentation: 'The intersection of the functionality described by the CapabilityStatement resources',
        )]
        public readonly ?CapabilityStatementResource $union = null,
        #[FhirOperationParameter(
            name: 'intersection',
            phpName: 'intersection',
            use: 'out',
            min: 0,
            max: '1',
            type: 'CapabilityStatement',
            documentation: 'The union of the functionality described by the CapabilityStatement resources',
        )]
        public readonly ?CapabilityStatementResource $intersection = null,
    ) {
    }
}
