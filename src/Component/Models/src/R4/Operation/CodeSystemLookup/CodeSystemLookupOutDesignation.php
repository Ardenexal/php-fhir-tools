<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Operation\CodeSystemLookup;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperationParameter;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperationPayload;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Coding;

#[FhirOperationPayload(
    operationUrl: 'http://hl7.org/fhir/OperationDefinition/CodeSystem-lookup',
    use: 'out',
    version: 'R4',
    operation: 'CodeSystemLookup',
    path: 'designation',
)]
final class CodeSystemLookupOutDesignation
{
    public function __construct(
        #[FhirOperationParameter(
            name: 'language',
            phpName: 'language',
            use: 'out',
            min: 0,
            max: '1',
            type: 'code',
            documentation: 'The language this designation is defined for',
        )]
        public readonly ?string $language = null,
        #[FhirOperationParameter(
            name: 'use',
            phpName: 'use',
            use: 'out',
            min: 0,
            max: '1',
            type: 'Coding',
            documentation: 'A code that details how this designation would be used',
        )]
        public readonly ?Coding $use = null,
        #[FhirOperationParameter(
            name: 'value',
            phpName: 'value',
            use: 'out',
            min: 1,
            max: '1',
            type: 'string',
            documentation: 'The text value for this designation',
        )]
        public readonly ?string $value = null,
    ) {
    }
}
