<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Operation\StructureDefinitionQuestionnaire;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperationParameter;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperationPayload;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Identifier;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\StructureDefinitionResource;

#[FhirOperationPayload(
    operationUrl: 'http://hl7.org/fhir/OperationDefinition/StructureDefinition-questionnaire',
    use: 'in',
    version: 'R5',
    operation: 'StructureDefinitionQuestionnaire',
    path: '',
)]
final class StructureDefinitionQuestionnaireInput
{
    public function __construct(
        #[FhirOperationParameter(
            name: 'identifier',
            phpName: 'identifier',
            use: 'in',
            min: 0,
            max: '1',
            type: 'Identifier',
            documentation: 'A logical identifier (i.e. \'StructureDefinition.identifier\'\'). The server must know the StructureDefinition or be able to retrieve it from other known repositories.',
        )]
        public readonly ?Identifier $identifier = null,
        #[FhirOperationParameter(
            name: 'profile',
            phpName: 'profile',
            use: 'in',
            min: 0,
            max: '1',
            type: 'StructureDefinition',
            documentation: 'The [StructureDefinition](structuredefinition.html) is provided directly as part of the request. Servers may choose not to accept profiles in this fashion',
        )]
        public readonly ?StructureDefinitionResource $profile = null,
        #[FhirOperationParameter(
            name: 'url',
            phpName: 'url',
            use: 'in',
            min: 0,
            max: '1',
            type: 'canonical',
            documentation: 'The StructureDefinition\'s official URL (i.e. \'StructureDefinition.url\'). The server must know the StructureDefinition or be able to retrieve it from other known repositories.',
            scope: ['type'],
        )]
        public readonly ?string $url = null,
        #[FhirOperationParameter(
            name: 'supportedOnly',
            phpName: 'supportedOnly',
            use: 'in',
            min: 0,
            max: '1',
            type: 'boolean',
            documentation: 'If true, the questionnaire will only include those elements marked as "mustSupport=\'true\'" in the StructureDefinition.',
        )]
        public readonly ?bool $supportedOnly = null,
    ) {
    }
}
