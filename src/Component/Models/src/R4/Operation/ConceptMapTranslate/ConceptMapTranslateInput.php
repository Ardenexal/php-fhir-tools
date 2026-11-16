<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Operation\ConceptMapTranslate;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperationParameter;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperationPayload;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Coding;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\ConceptMapResource;

#[FhirOperationPayload(
    operationUrl: 'http://hl7.org/fhir/OperationDefinition/ConceptMap-translate',
    use: 'in',
    version: 'R4',
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
        )]
        public readonly ?string $conceptMapVersion = null,
        #[FhirOperationParameter(
            name: 'code',
            phpName: 'code',
            use: 'in',
            min: 0,
            max: '1',
            type: 'code',
            documentation: 'The code that is to be translated. If a code is provided, a system must be provided',
        )]
        public readonly ?string $code = null,
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
            name: 'source',
            phpName: 'source',
            use: 'in',
            min: 0,
            max: '1',
            type: 'uri',
            documentation: 'Identifies the value set used when the concept (system/code pair) was chosen. May be a logical id, or an absolute or relative location. The source value set is an optional parameter because in some cases, the client cannot know what the source value set is. However, without a source value set, the server may be unable to safely identify an applicable concept map, and would return an error. For this reason, a source value set SHOULD always be provided. Note that servers may be able to identify an appropriate concept map without a source value set if there is a full mapping for the entire code system in the concept map, or by manual intervention',
        )]
        public readonly ?string $source = null,
        #[FhirOperationParameter(name: 'coding', phpName: 'coding', use: 'in', min: 0, max: '1', type: 'Coding', documentation: 'A coding to translate')]
        public readonly ?Coding $coding = null,
        #[FhirOperationParameter(
            name: 'codeableConcept',
            phpName: 'codeableConcept',
            use: 'in',
            min: 0,
            max: '1',
            type: 'CodeableConcept',
            documentation: 'A full codeableConcept to validate. The server can translate any of the coding values (e.g. existing translations) as it chooses',
        )]
        public readonly ?CodeableConcept $codeableConcept = null,
        #[FhirOperationParameter(
            name: 'target',
            phpName: 'target',
            use: 'in',
            min: 0,
            max: '1',
            type: 'uri',
            documentation: 'Identifies the value set in which a translation is sought. May be a logical id, or an absolute or relative location. If there\'s no target specified, the server should return all known translations, along with their source',
        )]
        public readonly ?string $target = null,
        #[FhirOperationParameter(
            name: 'targetsystem',
            phpName: 'targetsystem',
            use: 'in',
            min: 0,
            max: '1',
            type: 'uri',
            documentation: 'identifies a target code system in which a mapping is sought. This parameter is an alternative to the target parameter - only one is required. Searching for any translation to a target code system irrespective of the context (e.g. target valueset) may lead to unsafe results, and it is at the discretion of the server to decide when to support this operation',
        )]
        public readonly ?string $targetsystem = null,
        #[FhirOperationParameter(
            name: 'dependency',
            phpName: 'dependency',
            use: 'in',
            min: 0,
            max: '*',
            partClass: ConceptMapTranslateInDependency::class,
            documentation: 'Another element that may help produce the correct mapping',
        )]
        public readonly array $dependency = [],
        #[FhirOperationParameter(
            name: 'reverse',
            phpName: 'reverse',
            use: 'in',
            min: 0,
            max: '1',
            type: 'boolean',
            documentation: 'if this is true, then the operation should return all the codes that might be mapped to this code. This parameter reverses the meaning of the source and target parameters',
        )]
        public readonly ?bool $reverse = null,
    ) {
    }
}
