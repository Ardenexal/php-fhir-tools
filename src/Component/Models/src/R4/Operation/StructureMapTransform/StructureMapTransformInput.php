<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Operation\StructureMapTransform;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperationParameter;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperationPayload;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\AbstractResource;

#[FhirOperationPayload(
    operationUrl: 'http://hl7.org/fhir/OperationDefinition/StructureMap-transform',
    use: 'in',
    version: 'R4',
    operation: 'StructureMapTransform',
    path: '',
)]
final class StructureMapTransformInput
{
    public function __construct(
        #[FhirOperationParameter(
            name: 'source',
            phpName: 'source',
            use: 'in',
            min: 0,
            max: '1',
            type: 'uri',
            documentation: 'The structure map to apply. This is only needed if the operation is invoked at the resource level. If the $transform operation is invoked on a particular structure map, this will be ignored by the server',
        )]
        public readonly ?string $source = null,
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
