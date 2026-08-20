<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Operation\ConceptMapTranslate;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperationParameter;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperationPayload;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Coding;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\ConceptMapResource;

#[FhirOperationPayload(
    operationUrl: 'http://hl7.org/fhir/OperationDefinition/ConceptMap-translate',
    use: 'in',
    version: 'R5',
    operation: 'ConceptMapTranslate',
    path: '',
)]
final class ConceptMapTranslateInput
{
    /**
     * @param list<ConceptMapTranslateInDependency> $dependency
     */
    public function __construct(
        #[FhirOperationParameter(
            name: 'url',
            phpName: 'url',
            use: 'in',
            min: 0,
            max: '1',
            type: 'uri',
            documentation: 'A canonical URL for a concept map. The server must know the concept map (e.g. it is defined explicitly in the server\'s concept maps, or it is defined implicitly by some code system known to the server.',
            scope: ['type'],
        )]
        public readonly ?string $url = null,
        #[FhirOperationParameter(
            name: 'conceptMap',
            phpName: 'conceptMap',
            use: 'in',
            min: 0,
            max: '1',
            type: 'ConceptMap',
            documentation: 'The concept map is provided directly as part of the request. Servers may choose not to accept concept maps in this fashion.',
            scope: ['type'],
        )]
        public readonly ?ConceptMapResource $conceptMap = null,
        #[FhirOperationParameter(
            name: 'conceptMapVersion',
            phpName: 'conceptMapVersion',
            use: 'in',
            min: 0,
            max: '1',
            type: 'string',
            documentation: 'The identifier that is used to identify a specific version of the concept map to be used for the translation. This is an arbitrary value managed by the concept map author and is not expected to be globally unique. For example, it might be a timestamp (e.g. yyyymmdd) if a managed version is not available.',
            scope: ['type'],
        )]
        public readonly ?string $conceptMapVersion = null,
        #[FhirOperationParameter(
            name: 'sourceCode',
            phpName: 'sourceCode',
            use: 'in',
            min: 0,
            max: '1',
            type: 'code',
            documentation: 'The code that is to be translated. If a code is provided, a system must be provided',
        )]
        public readonly ?string $sourceCode = null,
        #[FhirOperationParameter(
            name: 'system',
            phpName: 'system',
            use: 'in',
            min: 0,
            max: '1',
            type: 'uri',
            documentation: 'The system for the code that is to be translated',
        )]
        public readonly ?string $system = null,
        #[FhirOperationParameter(
            name: 'version',
            phpName: 'version',
            use: 'in',
            min: 0,
            max: '1',
            type: 'string',
            documentation: 'The version of the system, if one was provided in the source data',
        )]
        public readonly ?string $version = null,
        #[FhirOperationParameter(
            name: 'sourceScope',
            phpName: 'sourceScope',
            use: 'in',
            min: 0,
            max: '1',
            type: 'uri',
            documentation: 'Limits the scope of the $translate operation to source codes (ConceptMap.group.element.code) that are members of this value set.',
        )]
        public readonly ?string $sourceScope = null,
        #[FhirOperationParameter(
            name: 'sourceCoding',
            phpName: 'sourceCoding',
            use: 'in',
            min: 0,
            max: '1',
            type: 'Coding',
            documentation: 'A coding to translate',
        )]
        public readonly ?Coding $sourceCoding = null,
        #[FhirOperationParameter(
            name: 'sourceCodeableConcept',
            phpName: 'sourceCodeableConcept',
            use: 'in',
            min: 0,
            max: '1',
            type: 'CodeableConcept',
            documentation: 'A full codeableConcept to validate. The server can translate any of the coding values (e.g. existing translations) as it chooses',
        )]
        public readonly ?CodeableConcept $sourceCodeableConcept = null,
        #[FhirOperationParameter(
            name: 'targetCode',
            phpName: 'targetCode',
            use: 'in',
            min: 0,
            max: '1',
            type: 'uri',
            documentation: 'The target code that is to be translated to. If a code is provided, a system must be provided',
        )]
        public readonly ?string $targetCode = null,
        #[FhirOperationParameter(
            name: 'targetCoding',
            phpName: 'targetCoding',
            use: 'in',
            min: 0,
            max: '1',
            type: 'uri',
            documentation: 'A target coding to translate to',
        )]
        public readonly ?string $targetCoding = null,
        #[FhirOperationParameter(
            name: 'targetCodeableConcept',
            phpName: 'targetCodeableConcept',
            use: 'in',
            min: 0,
            max: '1',
            type: 'uri',
            documentation: 'A full codeableConcept to validate. The server can translate any of the coding values (e.g. existing translations) as it chooses',
        )]
        public readonly ?string $targetCodeableConcept = null,
        #[FhirOperationParameter(
            name: 'targetScope',
            phpName: 'targetScope',
            use: 'in',
            min: 0,
            max: '1',
            type: 'uri',
            documentation: 'Limits the scope of the $translate operation to target codes (ConceptMap.group.element.target.code) that are members of this value set.',
        )]
        public readonly ?string $targetScope = null,
        #[FhirOperationParameter(
            name: 'targetSystem',
            phpName: 'targetSystem',
            use: 'in',
            min: 0,
            max: '1',
            type: 'uri',
            documentation: 'identifies a target code system in which a mapping is sought. This parameter is an alternative to the targetScope parameter - only one is required. Searching for any translation to a target code system irrespective of the context (e.g. target valueset) may lead to unsafe results, and it is at the discretion of the server to decide when to support this operation',
        )]
        public readonly ?string $targetSystem = null,
        #[FhirOperationParameter(
            name: 'dependency',
            phpName: 'dependency',
            use: 'in',
            min: 0,
            max: '*',
            partClass: ConceptMapTranslateInDependency::class,
            documentation: 'Data from another attribute that may help produce the correct mapping',
        )]
        public readonly array $dependency = [],
    ) {
    }
}
