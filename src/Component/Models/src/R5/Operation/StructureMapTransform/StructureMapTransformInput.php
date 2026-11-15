<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Operation\StructureMapTransform;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperationParameter;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperationPayload;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\AbstractResource;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\StructureMapResource;

#[FhirOperationPayload(
    operationUrl: 'http://hl7.org/fhir/OperationDefinition/StructureMap-transform',
    use: 'in',
    version: 'R5',
    operation: 'StructureMapTransform',
    path: '',
)]
final class StructureMapTransformInput
{
    /**
     * @param list<StructureMapResource> $supportingMap
     * @param list<string>               $srcMap
     */
    public function __construct(
        #[FhirOperationParameter(
            name: 'source',
            phpName: 'source',
            use: 'in',
            min: 0,
            max: '1',
            type: 'uri',
            documentation: 'The structure map to apply. This is only needed if the operation is invoked at the resource level and no structureMap has been provided. If the $transform operation is invoked on a particular structure map, this will be ignored by the server',
            scope: ['type'],
        )]
        public readonly ?string $source = null,
        #[FhirOperationParameter(
            name: 'sourceMap',
            phpName: 'sourceMap',
            use: 'in',
            min: 0,
            max: '1',
            type: 'StructureMap',
            documentation: 'The structure map to apply. This is only needed when the operation is invoked at the resource level and no URI has been provided.',
        )]
        public readonly ?StructureMapResource $sourceMap = null,
        #[FhirOperationParameter(
            name: 'supportingMap',
            phpName: 'supportingMap',
            use: 'in',
            min: 0,
            max: '*',
            type: 'StructureMap',
            documentation: 'StructureMap resources that support the source map. If a source URL is provided, the map can be provided in this parameter (or it can be provided as sourceMap).',
        )]
        public readonly array $supportingMap = [],
        #[FhirOperationParameter(
            name: 'srcMap',
            phpName: 'srcMap',
            use: 'in',
            min: 0,
            max: '*',
            type: 'string',
            documentation: 'The same as structureMap, but the resource is provided in the mapping language rather than as a structureMap.',
        )]
        public readonly array $srcMap = [],
        #[FhirOperationParameter(
            name: 'content',
            phpName: 'content',
            use: 'in',
            min: 1,
            max: '1',
            type: 'Resource',
            documentation: 'The logical content to transform',
        )]
        public readonly ?AbstractResource $content = null,
    ) {
    }
}
