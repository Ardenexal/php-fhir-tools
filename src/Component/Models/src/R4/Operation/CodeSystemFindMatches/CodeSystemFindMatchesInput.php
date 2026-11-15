<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Operation\CodeSystemFindMatches;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperationParameter;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperationPayload;

#[FhirOperationPayload(
    operationUrl: 'http://hl7.org/fhir/OperationDefinition/CodeSystem-find-matches',
    use: 'in',
    version: 'R4',
    operation: 'CodeSystemFindMatches',
    path: '',
)]
final class CodeSystemFindMatchesInput
{
    /**
     * @param list<CodeSystemFindMatchesInProperty> $property
     */
    public function __construct(
        #[FhirOperationParameter(
            name: 'system',
            phpName: 'system',
            use: 'in',
            min: 0,
            max: '1',
            type: 'uri',
            documentation: 'The system in which composition is to be performed. This must be provided unless the operation is invoked on a code system instance',
        )]
        public readonly ?string $system = null,
        #[FhirOperationParameter(
            name: 'version',
            phpName: 'version',
            use: 'in',
            min: 0,
            max: '1',
            type: 'string',
            documentation: 'The version of the system for the inferencing to be performed',
        )]
        public readonly ?string $version = null,
        #[FhirOperationParameter(
            name: 'property',
            phpName: 'property',
            use: 'in',
            min: 0,
            max: '*',
            partClass: CodeSystemFindMatchesInProperty::class,
            documentation: 'One or more properties that contain information to be composed into the code',
        )]
        public readonly array $property = [],
        #[FhirOperationParameter(
            name: 'exact',
            phpName: 'exact',
            use: 'in',
            min: 1,
            max: '1',
            type: 'boolean',
            documentation: 'Whether the operation is being used by a human (\'false\'), or a machine (\'true\'). If the operation is being used by a human, the terminology server can return a list of possible matches, with commentary. For a machine, the server returns complete or partial matches, not possible matches. The default value is \'false\'',
        )]
        public readonly ?bool $exact = null,
        #[FhirOperationParameter(
            name: 'compositional',
            phpName: 'compositional',
            use: 'in',
            min: 0,
            max: '1',
            type: 'boolean',
            documentation: 'Post-coordinated expressions are allowed to be returned in the matching codes (mainly for SNOMED CT). Default = false',
        )]
        public readonly ?bool $compositional = null,
    ) {
    }
}
