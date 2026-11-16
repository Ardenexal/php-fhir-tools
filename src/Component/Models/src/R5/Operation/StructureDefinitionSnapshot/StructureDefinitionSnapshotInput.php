<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Operation\StructureDefinitionSnapshot;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperationParameter;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperationPayload;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\StructureDefinitionResource;

#[FhirOperationPayload(
    operationUrl: 'http://hl7.org/fhir/OperationDefinition/StructureDefinition-snapshot',
    use: 'in',
    version: 'R5',
    operation: 'StructureDefinitionSnapshot',
    path: '',
)]
final class StructureDefinitionSnapshotInput
{
    public function __construct(
        #[FhirOperationParameter(
            name: 'definition',
            phpName: 'definition',
            use: 'in',
            min: 0,
            max: '1',
            type: 'StructureDefinition',
            documentation: 'The [StructureDefinition](structuredefinition.html) is provided directly as part of the request. Servers may choose not to accept profiles in this fashion',
            scope: ['type'],
        )]
        public readonly ?StructureDefinitionResource $definition = null,
        #[FhirOperationParameter(
            name: 'url',
            phpName: 'url',
            use: 'in',
            min: 0,
            max: '1',
            type: 'string',
            searchType: 'token',
            documentation: 'The StructureDefinition\'s canonical URL (i.e. \'StructureDefinition.url\'). The server must know the structure definition, or be able to retrieve it from other known repositories.',
            scope: ['type'],
        )]
        public readonly ?string $url = null,
    ) {
    }
}
