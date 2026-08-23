<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Operation\CodeSystemLookup;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperationParameter;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperationPayload;

#[FhirOperationPayload(
    operationUrl: 'http://hl7.org/fhir/OperationDefinition/CodeSystem-lookup',
    use: 'out',
    version: 'R5',
    operation: 'CodeSystemLookup',
    path: '',
)]
final class CodeSystemLookupOutput
{
    /**
     * @param list<CodeSystemLookupOutDesignation> $designation
     * @param list<CodeSystemLookupOutProperty>    $property
     */
    public function __construct(
        #[FhirOperationParameter(
            name: 'name',
            phpName: 'name',
            use: 'out',
            min: 1,
            max: '1',
            type: 'string',
            documentation: 'A display name for the code system',
        )]
        public readonly ?string $name = null,
        #[FhirOperationParameter(
            name: 'version',
            phpName: 'version',
            use: 'out',
            min: 0,
            max: '1',
            type: 'string',
            documentation: 'The version that these details are based on',
        )]
        public readonly ?string $version = null,
        #[FhirOperationParameter(
            name: 'display',
            phpName: 'display',
            use: 'out',
            min: 1,
            max: '1',
            type: 'string',
            documentation: 'The preferred display for this concept',
        )]
        public readonly ?string $display = null,
        #[FhirOperationParameter(
            name: 'definition',
            phpName: 'definition',
            use: 'out',
            min: 0,
            max: '1',
            type: 'string',
            documentation: 'A statement of the meaning of the concept from the code system',
        )]
        public readonly ?string $definition = null,
        #[FhirOperationParameter(
            name: 'designation',
            phpName: 'designation',
            use: 'out',
            min: 0,
            max: '*',
            partClass: CodeSystemLookupOutDesignation::class,
            documentation: 'Additional representations for this concept',
        )]
        public readonly array $designation = [],
        #[FhirOperationParameter(
            name: 'property',
            phpName: 'property',
            use: 'out',
            min: 0,
            max: '*',
            partClass: CodeSystemLookupOutProperty::class,
            documentation: 'One or more properties that contain additional information about the code, including status. For complex terminologies (e.g. SNOMED CT, LOINC, medications), these properties serve to decompose the code',
        )]
        public readonly array $property = [],
    ) {
    }
}
